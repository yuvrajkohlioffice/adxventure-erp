<?php

namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\{Invoice,User,Work,Payment,ProjectCategory,Lead,Category,TotalAmount,Projects,Followup,Template,Email,Office,Country,CustomRole,Bank};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class DatatableController extends Controller
{

    private function initializeLeadQuery($city)
    {
        $leads = Lead::where('user_id', '<>', auth()->user()->id);
        if($city!=null){
            $leads = $leads->where('city', $city);
        }
        $leads = $leads->with('category', 'totalAmount','Followup');
        return $leads;
    }

    public function index(Request $request) 
    {   
        try {
            if ($request->ajax()) {
                $city = null;
                if(isset($request->city)){
                    $city = $request->city;
                }
                $query = $this->initializeLeadQuery($city);
                $this->applyFilters($query, $request);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('checkbox', function($row) {
                        return '';
                    })
                    ->addColumn('client_info', function($row) {
                        $name = strtoupper($row->name);
                        $shortName = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;
                        return '<strong>' . htmlspecialchars($shortName, ENT_QUOTES, 'UTF-8') . '</strong><br>'
                            . '<small><a href="tel:' . htmlspecialchars($row->phone, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row->phone, ENT_QUOTES, 'UTF-8') . '</a></small><br>'
                            . '<small><a href="mailto:' . htmlspecialchars($row->email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row->email, ENT_QUOTES, 'UTF-8') . '</a></small>';
                    })
                    ->addColumn('pitch_service', function($row) {
                        $projectCategoryIds = json_decode($row->project_category, true);
                        if (!empty($projectCategoryIds)) {
                            $projectCategoryNames = \App\Models\ProjectCategory::whereIn('id', $projectCategoryIds)->pluck('name')->toArray();
                            return implode('<br>', $projectCategoryNames);
                        }
                        return 'No Service';
                    })
                    ->addColumn('country_city', function($row) {
                        $country = $row->countries->nicename ?? 'N/A';
                        $city = $row->city ?? 'N/A';
                        return '<strong>' . htmlspecialchars($country, ENT_QUOTES, 'UTF-8') . '</strong>, 
                                <small class="lead-city" data-id="' . htmlspecialchars($row->id, ENT_QUOTES, 'UTF-8') . '" 
                                    data-city="' . htmlspecialchars($city, ENT_QUOTES, 'UTF-8') . '" 
                                    style="cursor:pointer">(' . htmlspecialchars($city, ENT_QUOTES, 'UTF-8') . ')</small>';
                    })
                    ->addColumn('followup', function($row) {
                        $output = '';
                        $output .= '<a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#followup' . $row->id . '">
                                        <i class="fa fa-pencil"></i> Follow up';
                        $followUpCount = $row->followup->count();
                        if ($followUpCount >= 1) {
                            $output .= ' (' . $followUpCount . ')';
                        }
                        $output .= '</a><br>';
                        if ($followUpCount >= 1) {
                            $lastFollowUpDate = $row->followup->last()->created_at->format('d-m-y H:i:s');
                            $output .= '<small>(Last Follow-up: ' . $lastFollowUpDate . ')</small><br>';
                        }
                        $followups = $row->followup->where('delay', 1)->sortBy('created_at');
                        $delayedCount = $followups->count();
                        if ($delayedCount >= 1) {
                            $output .= '<small class="badge bg-danger">Delay: ' . $delayedCount . '</small><br>';
                        }
                        $lastFollowup = $row->followup->last();
                        if ($lastFollowup && $lastFollowup->next_date < \Carbon\Carbon::today()) {
                            $delayDays = \Carbon\Carbon::today()->diffInDays($lastFollowup->next_date);
                            if ($delayDays >= 1) {
                                $output .= '<small class="badge bg-danger">Last follow-up date expired: ' . $delayDays . ' days ago</small><br>';
                            }
                        }
                        return $output;
                    })
                    ->addColumn('proposal_mail', function($row) {
                        $buttons = '';
                        if ($row->email) {
                            $buttons .= '<a class="btn btn-sm btn-warning text-dark mx-0" style="cursor:pointer; float:right" onclick="SendMessage(' . $row->id . ')"><i class="bi bi-envelope"></i></a>';
                        }
                        if ($row->mail_status == 1) {
                            $buttons .= '<a href="' . route('prposel.mail.view', ['leadId' => $row->id]) . '" class="btn btn-sm btn-success mx-0">Resend Proposal</a>';
                            $buttons .= '<br><small>(Send Date: ' . \Carbon\Carbon::parse($row->mail_date)->format('d-m-y H:i:s') . ')</small><br>';
                        } else {
                            $buttons .= '<a href="' . route('lead.prposel.client', ['id' => $row->id]) . '" class="btn btn-sm btn-primary text-light mx-0">Send Proposal</a>';
                        }
                        return $buttons;
                    })
                    ->addColumn('lead_user', function($row) {
                        $assignedBy = $row->user->name ?? 'N/A';
                        $assignedUser = $row->AssignedUser->name ?? 'N/A';
                        return '<small>Lead User: <strong>' . htmlspecialchars($assignedBy, ENT_QUOTES, 'UTF-8') . '</strong></small><br>'
                            . '<small>Assigned by: <strong>' . htmlspecialchars($assignedBy, ENT_QUOTES, 'UTF-8') . '</strong></small><br>'
                            . '<small>Assigned User: <strong>' . htmlspecialchars($assignedUser, ENT_QUOTES, 'UTF-8') . '</strong></small>';
                    })
                    ->addColumn('action', function($row) {
                        $user = auth()->user();
                        $leadId = $row->id;
                        $status = $row->status;
                        $mailStatus = $row->mail_status;
                        $actionBtn = '';
                        if ($mailStatus == 1) {
                            $actionBtn .= '<div class="dropdown">
                                <button class="btn btn-outline-default" type="button" id="dropdownMenuButton'.$leadId.'" data-bs-toggle="dropdown" aria-expanded="false" style="background:#f2f2f2">
                                    <i class="bi bi-three-dots-vertical" style="font-weight: 900;font-size: 20px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton'.$leadId.'">
                                    <li><a href="'.route('lead.prposel.client', ['id' => $leadId]).'" class="dropdown-item">Custom Proposal</a></li>
                                    <li><a class="dropdown-item" href="'.route('prpeosal.view', ['id' => $leadId]).'">View Proposal</a></li>';
                            if ($status == 1 && $user->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])) {
                                $actionBtn .= '<li><a class="dropdown-item" href="'.url('/projects/create/'.$leadId).'">Add Project</a></li>
                                            <li><a class="dropdown-item" href="'.url('/lead/notes/'.$leadId).'">Add Note</a></li>';
                            }
                            $actionBtn .= '<li><a class="dropdown-item" href="'.url('/lead/edit/'.$leadId).'">Edit Lead</a></li>
                                        <li><a class="dropdown-item" href="'.route('lead.delete', ['id' => $leadId]).'">Delete Lead</a></li>
                                </ul>
                            </div>';
                        } else {
                            $actionBtn .= '<a href="" class="btn btn-sm btn-info">Edit</a>';
                        }
                        return $actionBtn;
                    })
                    // Add other columns as needed
                    ->rawColumns(['client_info', 'pitch_service', 'country_city', 'followup', 'proposal_mail', 'lead_user', 'action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            Log::error('DataTable Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }

        // Handle non-ajax requests
        $services = ProjectCategory::with('lead')->orderBy('name', 'asc')->get();
        $categories = Category::with('lead')->orderBy('name', 'asc')->get();
        $countries = Country::orderBy('nicename', 'asc')->get(['id', 'nicename', 'phonecode']);

        return view('admin.crm.datatable', compact('services', 'countries', 'categories'));
    }


private function getDataForView(Request $request)
{
    $projectCategories = ProjectCategory::all();
    $leadCounts = $this->initializeLeadCounts();
    $userRoleData = $this->handleRoleBasedLogic(auth()->user(), $this->initializeLeadQuery());
    $users = $this->retrieveUsers(auth()->user());
    $reports = $this->reports(auth()->user());
    $categories = Category::with('lead')->orderBy('name', 'asc')->get();
    $services = ProjectCategory::with('lead')->orderBy('name', 'asc')->get();
    $templates = Template::where('category', 'common')->where('type', 1)->orderBy('title', 'asc')->get();
    $messagetemplates = Template::where('category', 'common')->where('type', 3)->orderBy('title', 'asc')->get();
    $countries = Country::orderBy('nicename', 'asc')->get(['id', 'nicename', 'phonecode']);
    
    return array_merge([
        'projectCategories' => $projectCategories,
        'users' => $users,
        'categories' => $categories,
        'services' => $services,
        'client_name' => $request->input('client_name'),
        'lead_status' => $request->input('lead_status'),
        'date' => $request->input('date'),
        'category' => $request->input('category'),
        'service' => $request->input('service'),
        'from_date' => $request->input('from_date'),
        'to_date' => $request->input('to_date'),
        'templates' => $templates,
        'messagetemplates' => $messagetemplates,
        'countries' => $countries,
        'bdeReports' => $reports,
    ], $leadCounts, $userRoleData);
}



private function applyFilters($query, Request $request)
{
    if ($request->has('client_name')) {
        $clientName = $request->input('client_name');
        $query->where(function ($q) use ($clientName) {
            $q->where('name', 'like', '%' . $clientName . '%')
                ->orWhere('email', 'like', '%' . $clientName . '%')
                ->orWhere('phone', 'like', '%' . $clientName . '%')
                ->orWhere('country', 'like', '%' . $clientName . '%')
                ->orWhere('city', 'like', '%' . $clientName . '%');
        });
    }

    if($request->has('country')){
        $query->where('country', $request->input('country'));
    }

    if ($request->has('lead_day')) {
        $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
    }

    if ($request->has('lead_status')) {
        $status = $request->input('lead_status');
        if($status == 7){
            $query->where('status', $status !== null ? 1 : 0);
        }else{
            $query->where('lead_status', $status !== null ? $status : 0);
        }
    }

    if ($request->has('category')) {
        $query->where('client_category', $request->input('category'));
    }

    if ($request->has('service')) {
        $services = $request->input('service');
        
        // Convert service IDs to integers
        if (is_array($services)) {
            $services = array_map('intval', $services);
        } else {
            $services = intval($services);
        }

        // Apply the filter with the adjusted service IDs
        if (is_array($services)) {
            $query->where(function ($q) use ($services) {
                foreach ($services as $service) {
                    $q->orWhereJsonContains('project_category', $service);
                }
            });
        } else {
            $query->whereJsonContains('project_category', $services);
        }
    }

    if($request->has('proposal')){
        $this->applyProposalFilter($query, $request->input('proposal'), $request);
    }

    if($request->has('followup')){
        $this->applyFollowupFilter($query, $request->input('followup'), $request);
    }
}

private function applyLeadDayFilter($query, $leadDay, Request $request)
{
    switch ($leadDay) {
        case 'today':
            $query->whereDate('created_at', Carbon::today());
            break;
        case 'month':
            $query->whereMonth('created_at', Carbon::now()->month);
            break;
        case 'year':
            $query->whereYear('created_at', Carbon::now()->year);
            break;
        case 'custome':
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = Carbon::parse($request->input('from_date'));
                $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
        break;
    }
}

private function applyProposalFilter($query, $proposal, Request $request)
{
    switch ($proposal) {
        case 'today':
            $query->whereDate('mail_date', Carbon::today())->where('mail_status',1);
            break;
        case 'month':
            $query->whereMonth('mail_date', Carbon::now()->month)->where('mail_status',1);
            break;
        case 'year':
            $query->whereYear('mail_date', Carbon::now()->year)->where('mail_status',1);
            break;
        case 'custome':
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = Carbon::parse($request->input('from_date'));
                $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
                $query->whereBetween('created_at', [$fromDate, $toDate])->where('mail_status',1);
            }
        break;
    }
}

private function applyFollowupFilter($query, $followup, Request $request){
    switch ($followup) {
        case 'today':
            $query->whereHas('Followup', function($q) {
                $q->whereDate('created_at', now()->toDateString());
            });
            break;
        case 'this_week':
            $query->whereHas('Followup', function($q) {
                $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            });
            break;
        case 'month':
            $query->whereHas('Followup', function($q) {
                $q->whereMonth('created_at', now()->month);
            });
        break;
        case 'today_followup':
            $query->whereHas('Followup', function($q) {
                $q->whereDate('next_date',  now()->toDateString());
            });
            break;
        case 'today_converted':
            $query->whereDate('created_at', Carbon::today())->where('status',1);
        break;
    }
    return $query;

}

private function initializeLeadCounts()
{
    return [
        'today_leads' => 0,
        'month_leads' => 0,
        'year_leads' => 0,
        'convert_leads' => 0,
        'fresh_lead' => 0,
        'total_leads' => 0,
        'today_followup' => 0,
        'total_followup' => 0,
        'today_proposal' => 0,
        'total_proposal' => 0,
        'today_fresh' => 0,
        'followup_today' => 0,
        'followup_completed_today' =>0,
        'total_amount' => 0,
        'total_revanue' =>0,
        'today_converted' => 0,
        'converted_completed_today' => 0,
    ];
}

private function handleRoleBasedLogic($user, $query)
{
    $data = [];
    if ($user && $user->hasRole('BDE')) {
        $userId = $user->id;
        $data['today_leads'] = Lead::where('created_at', '>=', Carbon::today())
                                   ->where(function($q) use ($userId) {
                                       $q->where('user_id', $userId)
                                         ->orWhere('assigned_user_id', $userId);
                                   })->count();
        $data['month_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfMonth())
                                   ->where(function($q) use ($userId) {
                                       $q->where('user_id', $userId)
                                         ->orWhere('assigned_user_id', $userId);
                                   })->count();
        $data['year_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfYear())
                                  ->where(function($q) use ($userId) {
                                      $q->where('user_id', $userId)
                                        ->orWhere('assigned_user_id', $userId);
                                  })->count();
        $data['convert_leads'] = Lead::where('status', 1)
                                     ->where(function($q) use ($userId) {
                                         $q->where('user_id', $userId)
                                           ->orWhere('assigned_user_id', $userId);
                                     })->count();
        $data['total_leads'] = Lead::where(function($q) use ($userId) {
                                         $q->where('user_id', $userId)
                                           ->orWhere('assigned_user_id', $userId);
                                     })->count();
        $data['leads'] = $query->with('countries')->where(function($q) use ($userId) {
                                  $q->where('user_id', $userId)
                                    ->orWhere('assigned_user_id', $userId);
                              })->orderBy('id', 'desc')->paginate(20);
                              
        $data['today_fresh'] = Lead::where('status', '!=', 1)
                                   ->where('created_at', '>=', Carbon::today())
                                   ->where(function($q) use ($userId) {
                                       $q->where('user_id', $userId)
                                         ->orWhere('assigned_user_id', $userId);
                                   })->count();
        $data['fresh_lead'] = Lead::where('status', '!=', 1)
                                  ->where(function($q) use ($userId) {
                                      $q->where('user_id', $userId)
                                        ->orWhere('assigned_user_id', $userId);
                                  })->count();
        $data['today_proposal'] = DB::table('prposal')->whereNotNull('lead_id')
                                                       ->where('created_at', '>=', Carbon::today())
                                                       ->where('user_id', $userId)->count();
        $data['total_proposal'] = DB::table('prposal')->whereNotNull('lead_id')
                                                       ->where('user_id', $userId)->count();
        $data['today_followup'] = Followup::whereNotNull('lead_id')
                                          ->where('created_at', '>=', Carbon::today())
                                          ->where('user_id', $userId)->count();
        $data['total_followup'] = Followup::whereNotNull('lead_id')
                                          ->where('user_id', $userId)->count();

     
        $data['followup_today'] = Followup::whereNotNull('lead_id')
                                            ->where('next_date', Carbon::today())
                                            ->where('user_id', $userId)
                                            ->count();
        $data['followup_completed_today'] =  Followup::whereNotNull('lead_id')
                                                        ->where('is_completed',1)
                                                        ->where('user_id', $userId)
                                                        ->where('next_date', Carbon::today())->count();

        $data['total_amount'] = 0;
        
        
        $data['total_revenue'] =0;
        $data['today_converted'] = Lead::where('status', 1)
                                    ->whereDate('created_at',Carbon::today())
                                     ->where(function($q) use ($userId) {
                                         $q->where('user_id', $userId)
                                           ->orWhere('assigned_user_id', $userId);
                                     })->count();
        // $data['total_revenue'] = Payment::where('lead_id', $query->id)
        //                                 ->whereDate('created_at', Carbon::today())
        //                                 ->sum('amount');
    } else {
        $data['today_leads'] = Lead::where('created_at', '>=', Carbon::today())->count();
        $data['month_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $data['year_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfYear())->count();
        $data['convert_leads'] = Lead::where('status', 1)->count();
        $data['total_leads'] = Lead::count();
        $data['leads'] = $query->with('countries')->orderBy('id', 'desc')->paginate(20);
        $data['today_fresh'] = Lead::where('status', '!=', 1)->where('created_at', '>=', Carbon::today())->count();
        $data['fresh_lead'] = Lead::where('status', '!=', 1)->count();
        $data['today_proposal'] = DB::table('prposal')->whereNotNull('lead_id')->where('created_at', '>=', Carbon::today())->count();
        $data['total_proposal'] = DB::table('prposal')->whereNotNull('lead_id')->count();
        $data['today_followup'] = Followup::whereNotNull('lead_id')->where('created_at', '>=', Carbon::today())->count();
        $data['total_followup'] = Followup::whereNotNull('lead_id')->count();

        $data['followup_today'] = $data['today_leads'] + Followup::whereNotNull('lead_id')->whereDate('next_date',Carbon::today())->count();
    
        $data['followup_completed_today'] =  Followup::whereNotNull('lead_id')->where('is_completed',1)->where('next_date',Carbon::today())->count();
        $data['total_amount'] = TotalAmount::whereIn('lead_id', $query->pluck('id'))
        ->whereDate('created_at', Carbon::today())
        ->sum('total_amount');
        $data['total_revenue'] = Payment::where('lead_id', $query->first()->id ??0)
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');
        $data['today_converted'] = Lead::where('status', 1)->whereDate('created_at',Carbon::today())->count();
    }
    return $data;
}


private function retrieveUsers($user)
{
    $userRole = $user->roles()->pluck('name')->toArray();
    if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole)) {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Marketing-Manager', 'BDE']);
        })->get();
    }
    return User::whereHas('roles', function ($query) {
        $query->where('name', 'BDE');
    })->get();
}

public function reports($user)
{
    $userRole = $user->roles()->pluck('name')->toArray();
    $data = [];

    if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole)) {
        // Retrieve all BDEs
        $bdeUsers = User::orderBy('name','asc')->whereHas('roles', function ($query) {
            $query->where('name', 'BDE');
        })->get();
        
        // Prepare arrays to hold counts
        $bdeReports = [];

        // Aggregate counts for each BDE
        foreach ($bdeUsers as $bde) {
            $bdeId = $bde->id;

            $bdeReports[] = [
                'name' => $bde->name,
                'assigned_leads' => Lead::whereDate('created_at', Carbon::today())
                    ->where(function($q) use ($bdeId) {
                        $q->where('user_id', $bdeId)
                        ->orWhere('assigned_user_id', $bdeId);
                    })->count(),
                'followups' => Followup::whereNotNull('lead_id')
                    ->whereDate('created_at', Carbon::today())
                    ->where('user_id', $bdeId)
                    ->count(),
                'calls' => Followup::whereNotNull('lead_id')
                            ->whereDate('created_at', Carbon::today())
                            ->where('user_id', $bdeId)
                            ->count(), // Adjust this as needed
                'proposals' => DB::table('prposal')
                    ->whereNotNull('lead_id')
                    ->whereDate('created_at', Carbon::today())
                    ->where('user_id', $bdeId)
                    ->count(),
                'converted' => Lead::whereDate('created_at', Carbon::today())
                    ->where('status',1) // Adjust according to your logic
                    ->where(function($q) use ($bdeId) {
                        $q->where('user_id', $bdeId)
                        ->orWhere('assigned_user_id', $bdeId);
                    })->count()
            ];
        }

        $data = [
            'users' => $bdeUsers,
            'bdeReports' => $bdeReports
        ];
    } else {
        // For non Super-Admin/Admin users
        $data = [
            'users' => User::whereHas('roles', function ($query) {
                $query->where('name', 'BDE');
            })->get(),
            'bdeReports' => [] // Return an empty array for non-admins
        ];
    }

    return $data;
}


}