<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Csv\Reader;
use League\Csv\Statement;

use App\Models\{Invoice, User, Work, Payment, ProjectCategory, Lead, Category, TotalAmount, Projects, Followup, Template, Email, Office, Country, CustomRole, Bank, Proposal, Expenses, ProjectInvoice, Message, Api};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use App\Helpers\EncodingHelper;

class CrmController extends Controller
{
    // ==========================================
    //  OPTIMIZED DASHBOARD & REPORTING METHODS
    // ==========================================

    private function initializeLeadQuery()
    {
        return Lead::with(['category', 'totalAmount', 'countries', 'latestFollowup']);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        // 1. Retrieve Data (Optimized)
        $users = $this->retrieveUsers($user);
        $bdeReports = $this->reports($user); 

        // Simple optimized gets
        $projectCategories = ProjectCategory::all(); 
        $categories = Category::withCount('lead')->orderBy('name', 'asc')->get(); 
        $services = ProjectCategory::orderBy('name', 'asc')->get();
        $countries = Country::orderBy('nicename', 'asc')->select('id', 'nicename', 'phonecode')->get();
        $messagetemplates = Template::where('category', 'common')->where('type', 3)->orderBy('title', 'asc')->get();

        // 2. Prepare User Role Data for Table
        $query = $this->initializeLeadQuery();
        
        // This calculates the $data array (counters) and applies filters to the $query
        $userRoleData = $this->handleRoleBasedLogic($user, $query);

        // Extract counts from the returned data for the view
        // Note: handleRoleBasedLogic returns an array $data with keys like 'total_leads', etc.
        // We map these to the $count variable expected by the view.
        $count = [
            'leads'     => $userRoleData['today_leads'] ?? 0,
            'followups' => $userRoleData['today_followup'] ?? 0,
            'proposals' => $userRoleData['today_proposal'] ?? 0,
            'quotation' => $userRoleData['quotation'] ?? 0, // Ensure this key exists in handleRoleBasedLogic
            'delay'     => $userRoleData['delay'] ?? 0,
            'reject'    => $userRoleData['total_reject'] ?? 0,
            'revenue'   => $userRoleData['total_revenue'] ?? 0,
        ];

        // Pass the actual table data (paginated) as $userRoleData for the view's loop
        $tableData = $userRoleData['leads'];

        return view('admin.crm.index', compact('tableData', 'userRoleData', 'users', 'countries', 'services', 'projectCategories', 'categories', 'messagetemplates', 'bdeReports', 'count'));
    }

    public function data(Request $request)
    {
        $projectCategories = ProjectCategory::pluck('name', 'id')->toArray();
        $user = auth()->user();
        
        $query = Lead::with([
            'category', 
            'countries', 
            'user:id,name', 
            'AssignedUser:id,name', 
            'invoice',
            'Followup' => fn($q) => $q->latest()->limit(1)
        ])
        ->withCount(['Followup as total_followups']);

        if ($user && $user->hasRole(['BDE', 'Business Development Intern'])) {
            $query->where('assigned_user_id', $user->id);
        }

        $this->applyFilters($query, $request);
        $query->orderByDesc('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('client_info', function ($lead) {
                $lead = EncodingHelper::sanitizeUtf8($lead);
                $daysDiff = now()->diffInDays($lead->created_at);
                $maskedMobile = substr($lead->phone, 0, 5) . '******';
                $categoryName = $lead->category->name ?? 'N/A';
                
                $statusBadge = match (true) {
                    $lead->status == 1      => '<span class="badge bg-success">Convert</span>',
                    $lead->lead_status == 1 => '<span class="badge bg-danger">Hot</span>',
                    $lead->lead_status == 2 => '<span class="badge bg-warning">Warm</span>',
                    default                 => '<span class="badge bg-primary">Cold</span>',
                };

                $leadSource = match ((int)$lead->lead_source) {
                    1 => 'Website', 2 => 'Social Media', 3 => 'Reference', default => 'Bulk Lead',
                };

                $editBtn = '';
                if ($lead->status != 1) {
                    $jsParams = htmlspecialchars(json_encode([
                        $lead->id, $lead->name, $lead->email, $lead->country, $lead->phone,
                        $lead->city, $lead->client_category, $lead->website, $lead->domian_expire,
                        $lead->lead_status, $lead->lead_source, $lead->ref_name, $lead->assigned_user_id
                    ]), ENT_QUOTES, 'UTF-8');
                    $jsParams = trim($jsParams, '[]'); 
                    $editBtn = "<span class='badge text-dark' style='cursor:pointer' onclick='EditLead($jsParams)'>Edit</span>";
                }

                $lastFollowup = $lead->Followup->first();
                $delayBadge = ($lastFollowup && $lastFollowup->next_date < now()) 
                    ? "<span class='badge bg-danger'>Last followup expired: ".now()->diffInDays($lastFollowup->next_date)." days ago</span>" 
                    : "";

                $emailLink = !empty($lead->email) ? "<small><a href='mailto:{$lead->email}'><i class='bi bi-envelope'></i> {$lead->email}</a></small><br>" : "";
                $daysAgoBadge = $daysDiff > 1 ? "<span class='badge bg-secondary'>{$daysDiff} Days Ago</span>" : "";
                
                return "
                    <div class='order-md-1'>
                        <h6 class='mb-1 text-dark fs-15 lead-name fw-bold' data-id='{$lead->id}' data-name='{$lead->name}' style='cursor:pointer'>".substr(ucfirst($lead->name), 0, 20)."..</h6>
                        <small class='text-muted'>({$categoryName})</small> | 
                        <small class='text-muted'>{$editBtn}</small> | 
                        <small class='badge text-muted bg-muted'>{$leadSource}</small> |  
                        {$statusBadge} <br>
                        <small onclick=\"Followup({$lead->id}, '{$lead->name}', '{$lead->phone}', 0)\">
                            <a href='#'><i class='bi bi-telephone'></i> {$maskedMobile}</a>
                        </small><br>
                        {$emailLink}
                        <small><i class='bi bi-bag-plus'></i> Create Date: {$lead->created_at->format('d-m-y H:i:s')}</small>
                        {$daysAgoBadge}<br>
                        {$delayBadge}
                    </div>";
            })
            ->addColumn('service', function ($lead) use ($projectCategories) {
                if (empty($lead->project_category)) return 'No Service';
                $ids = is_array($lead->project_category) ? $lead->project_category : json_decode($lead->project_category, true);
                if (!is_array($ids)) return 'Invalid Data';
                $names = [];
                foreach ($ids as $id) {
                    if (isset($projectCategories[$id])) $names[] = $projectCategories[$id];
                }
                return implode('<br>', $names);
            })
            ->addColumn('location', function ($lead) {
                $c = $lead->countries->nicename ?? 'N/A';
                return "<strong class='lead-country' data-id='{$lead->id}' data-country='{$c}' style='cursor:pointer'><i class='bi bi-geo-alt-fill'></i> {$c}</strong><br><small class='lead-city' data-id='{$lead->id}' data-city='{$lead->city}' style='cursor:pointer'>({$lead->city})</small>";
            })
            ->addColumn('followup', function ($lead) {
                $count = $lead->total_followups;
                $countHtml = $count >= 1 ? "({$count})" : "";
                $last = $lead->Followup->first();
                $lastHtml = $last ? "<small>(Last Follow-up: {$last->created_at->format('d-m-y H:i:s')})</small><br><small>Reason: {$last->reason}</small><br>" : "";
                return "<a class='btn btn-primary btn-sm' onclick=\"Followup({$lead->id}, '{$lead->name}', '{$lead->phone}', 1)\">Follow up {$countHtml}</a><br>{$lastHtml}";
            })
            ->addColumn('quotation', function ($lead) {
                $btn = "<a class=\"btn btn-sm btn-outline-warning\" onclick=\"SendMessage({$lead->id})\" data-bs-toggle=\"tooltip\" title=\"Send Portfolio\"><i class=\"bi bi-chat-dots\"></i> Send Portfolio</a><br><a class=\"btn btn-sm btn-outline-primary mt-2\" onclick=\"SendProposal({$lead->id})\" data-bs-toggle=\"tooltip\" title=\"Send Proposal\"><i class=\"bi bi-file-text\"></i> Send Proposal</a><br>";
                if ($lead->quotation == 1) {
                    $url = route('crm.prposel.mail.view', ['leadId' => $lead->id]);
                    $date = $lead->quotation_date ? Carbon::parse($lead->quotation_date)->format('d-m-y H:i:s') : '';
                    $btn .= "<a href=\"{$url}\" class=\"mt-2 btn btn-sm btn-outline-success\" data-bs-toggle=\"tooltip\" title=\"Resend Quotation\"><i class=\"bi bi-file-earmark-arrow-up\"></i> Resend Quotation</a><br><small>(Send Date: {$date})</small>";
                } else {
                    $url = route('crm.quotation.client', ['id' => $lead->id]);
                    $btn .= "<a href=\"{$url}\" class=\"mt-2 btn btn-sm btn-outline-success\" data-bs-toggle=\"tooltip\" title=\"Send Quotation\"><i class=\"bi bi-file-earmark-arrow-up\"></i> Send Quotation</a>";
                }
                return $btn;
            })
            ->addColumn('assigned_info', function ($lead) {
                $creator = $lead->user->name ?? 'N/A';
                $assignee = $lead->AssignedUser->name ?? 'N/A';
               return "<small>Created by : <strong>{$creator}</strong></small><br><small>Assigned by: <strong>{$creator}</strong></small><br><small>Assigned User: <strong>{$assignee}</strong></small>";
            })
            ->addColumn('actions', function ($lead) {
                if ($lead->quotation != 1) return '-';
                $viewUrl = route('crm.prposel.mail.view', ['leadId' => $lead->id]);
                $paid = $lead->invoice ? "<li><a class='dropdown-item' onclick=\"MarkAsPaid({$lead->invoice->id}, {$lead->invoice->balance}, '{$lead->name}')\">Mark as Paid</a></li>" : "";
                return "<div class='dropdown'><button class='btn btn-outline-default' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button><ul class='dropdown-menu'>{$paid}<li><a class='dropdown-item' href='{$viewUrl}'>View Quotation</a></li></ul></div>";
            })
            ->rawColumns(['checkbox', 'client_info', 'service', 'location', 'followup', 'quotation', 'assigned_info', 'actions'])
            ->make(true);
    }

    public function reports($user)
    {
        $userRole = $user->roles()->pluck('name')->toArray();
        $today = Carbon::today();

        if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole)) {
            // Fetch users first
            $bdeUsers = User::where('is_active', 1)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['BDE', 'Business Development Intern']);
                })
                ->orderBy('name', 'asc')
                ->get();

            $bdeIds = $bdeUsers->pluck('id')->toArray();

            // Bulk fetch statistics for all users at once (Eliminates N+1)
            $leadsToday = Lead::select('id', 'user_id', 'assigned_user_id', 'status')
                ->whereDate('created_at', $today)
                ->where(function($q) use ($bdeIds) {
                    $q->whereIn('user_id', $bdeIds)->orWhereIn('assigned_user_id', $bdeIds);
                })->get();

            $proposalsToday = Lead::select('id', 'user_id', 'assigned_user_id')
                ->where('proposal', 1)->whereDate('proposal_date', $today)
                ->where(function($q) use ($bdeIds) {
                    $q->whereIn('user_id', $bdeIds)->orWhereIn('assigned_user_id', $bdeIds);
                })->get();

            $quotationsToday = Lead::select('id', 'user_id', 'assigned_user_id')
                ->where('quotation', 1)->whereDate('quotation_date', $today)
                ->where(function($q) use ($bdeIds) {
                    $q->whereIn('user_id', $bdeIds)->orWhereIn('assigned_user_id', $bdeIds);
                })->get();

            $followupsToday = Followup::select('id', 'user_id', 'lead_id')
                ->whereHas('lead')
                ->whereDate('created_at', $today)
                ->whereIn('user_id', $bdeIds)
                ->get();

            // Map data in memory
            $bdeReports = $bdeUsers->map(function ($bde) use ($leadsToday, $proposalsToday, $quotationsToday, $followupsToday) {
                $id = $bde->id;
                $isOwner = fn($item) => $item->user_id == $id || $item->assigned_user_id == $id;

                return [
                    'name' => $bde->name,
                    'role' => $bde->roles->first()->name ?? 'N/A',
                    'image' => $bde->image,
                    'email' => $bde->email,
                    'phone' => $bde->phone_no,
                    'id' => $bde->id,
                    'assigned_leads' => $leadsToday->filter($isOwner)->count(),
                    'converted' => $leadsToday->filter(fn($l) => $isOwner($l) && $l->status == 1)->count(),
                    'proposals' => $proposalsToday->filter($isOwner)->count(),
                    'quotation' => $quotationsToday->filter($isOwner)->count(),
                    'followups' => $followupsToday->where('user_id', $id)->unique('lead_id')->count(),
                ];
            });

            return ['users' => $bdeUsers, 'bdeReports' => $bdeReports];
        }

        return [
            'users' => User::whereHas('roles', fn($q) => $q->where('name', 'BDE'))->get(),
            'bdeReports' => []
        ];
    }

    private function handleRoleBasedLogic($user, $query)
    {
        $data = [];
        $today = Carbon::today();
        $isBDE = $user && $user->hasRole(['BDE', 'Business Development Intern']);
        $userId = $user->id;

        $leadScope = Lead::query();
        if ($isBDE) {
            $leadScope->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('assigned_user_id', $userId);
            });
        }

        $followupScope = Followup::query();
        if ($isBDE) {
            $followupScope->whereHas('lead', function ($q) use ($userId) {
                $q->where('assigned_user_id', $userId)->orWhere('user_id', $userId);
            });
        } else {
            $followupScope->whereHas('lead');
        }

        $proposalScope = DB::table('prposal')->whereNotNull('lead_id');
        if ($isBDE) {
            $proposalScope->where('user_id', $userId);
        }

        // --- Aggregations ---
        $leadStats = $leadScope->selectRaw("
            count(*) as total,
            count(case when created_at >= ? then 1 end) as today,
            count(case when created_at >= ? then 1 end) as month,
            count(case when created_at >= ? then 1 end) as year,
            count(case when status = 1 then 1 end) as converted,
            count(case when status = 1 and date(created_at) = ? then 1 end) as converted_today,
            count(case when status != 1 then 1 end) as fresh,
            count(case when status != 1 and created_at >= ? then 1 end) as fresh_today
        ", [$today, Carbon::now()->startOfMonth(), Carbon::now()->startOfYear(), $today, $today])->first();

        // Calculate quotations separately if needed specifically for the count array
        $quotationCount = Lead::whereDate('quotation_date', $today)->where('quotation', 1);
        if($isBDE) {
            $quotationCount->where(function($q) use ($userId){ $q->where('user_id', $userId)->orWhere('assigned_user_id', $userId); });
        }
        $data['quotation'] = $quotationCount->count();

        $proposalStats = $proposalScope->selectRaw("
            count(*) as total,
            count(case when created_at >= ? then 1 end) as today
        ", [$today])->first();

        $followupStats = $followupScope->selectRaw("
            count(distinct lead_id) as total_distinct,
            count(distinct case when date(created_at) = ? then lead_id end) as created_today,
            count(distinct case when date(next_date) = ? then lead_id end) as next_date_today,
            count(distinct case when date(created_at) = ? or date(next_date) = ? then lead_id end) as yesterday,
            count(distinct case when (created_at between ? and ?) or (next_date between ? and ?) then lead_id end) as last_7_days,
            count(distinct case when (created_at between ? and ?) or (next_date between ? and ?) then lead_id end) as this_month,
            count(distinct case when is_completed = 1 then lead_id end) as completed,
            count(distinct case when is_completed != 1 or is_completed is null then lead_id end) as pending,
            count(distinct case when is_completed = 1 and date(next_date) = ? then lead_id end) as completed_today,
            count(distinct case when (is_completed != 1 or is_completed is null) and date(next_date) = ? then lead_id end) as pending_today,
            count(distinct case when reason = 'Wrong Information' then lead_id end) as reject_wrong_info,
            count(distinct case when reason = 'Work with other company' then lead_id end) as reject_other,
            count(distinct case when reason = 'Not interested' then lead_id end) as reject_not_interested,
            count(distinct case when reason in ('Wrong Information', 'Work with other company', 'Not interested') then lead_id end) as total_reject,
            count(distinct case when reason in ('Wrong Information') and date(created_at) = ? then lead_id end) as today_reject_wrong_info,
            count(distinct case when reason in ('call back later', 'Not pickup') then lead_id end) as cold,
            count(distinct case when reason in ('call back later', 'Not pickup') and date(created_at) = ? then lead_id end) as cold_today,
            count(distinct case when reason = 'Payment Tomorrow' then lead_id end) as payment_tomorrow,
            count(distinct case when reason = 'Other' then lead_id end) as other,
            count(distinct case when reason = 'Interested' then lead_id end) as interested,
            count(distinct case when (delay = 1 or is_completed != 1) then lead_id end) as delay_total,
            count(distinct case when (delay = 1 or is_completed != 1) and date(next_date) = ? then lead_id end) as delay_today
        ", [
            $today, 
            $today, 
            Carbon::yesterday(), Carbon::yesterday(),
            Carbon::now()->subDays(7), Carbon::now(), Carbon::now()->subDays(7), Carbon::now(),
            Carbon::now()->startOfMonth(), Carbon::now(), Carbon::now()->startOfMonth(), Carbon::now(),
            $today, 
            $today, 
            $today, 
            $today, 
            $today  
        ])->first();

        // Populate return array
        $data['total_leads'] = $leadStats->total;
        $data['today_leads'] = $leadStats->today;
        $data['month_leads'] = $leadStats->month;
        $data['year_leads'] = $leadStats->year;
        $data['convert_leads'] = $leadStats->converted;
        
        $data['leads'] = $query->orderBy('id', 'desc')->paginate(20);

        $data['today_fresh'] = $leadStats->fresh_today;
        $data['fresh_lead'] = $leadStats->fresh;
        
        $data['today_proposal'] = $proposalStats->today;
        $data['total_proposal'] = $proposalStats->total;

        $data['today_created_followup'] = $followupStats->created_today;
        $data['today_followup'] = $followupStats->next_date_today;
        $data['yesterday_followup'] = $followupStats->yesterday;
        $data['last7Days_followup'] = $followupStats->last_7_days;
        $data['thisMonth_followup'] = $followupStats->this_month;
        
        $activeFollowupScope = clone $followupScope;
        $data['total_followup'] = $activeFollowupScope
            ->whereNotIn('reason', ['Work with other company', 'Wrong Information', 'Not interested'])
            ->distinct('lead_id')
            ->count('lead_id');

        if ($isBDE) {
            $data['followup_today'] = $followupStats->next_date_today;
        } else {
            $data['followup_today'] = $leadStats->today + $followupStats->next_date_today;
        }

        $data['today_complated_followup'] = $followupStats->completed_today;
        $data['today_pending_followup'] = $followupStats->pending_today;

        if ($isBDE) {
            $data['total_amount'] = 0; 
            $data['total_revenue'] = 0; 
        } else {
            $leadIds = $data['leads']->pluck('id');
            if($leadIds->isNotEmpty()) {
               $data['total_amount'] = TotalAmount::whereIn('lead_id', $leadIds)->whereDate('created_at', $today)->sum('total_amount');
               $data['total_revenue'] = Payment::whereIn('lead_id', $leadIds)->whereDate('created_at', $today)->sum('amount');
            } else {
               $data['total_amount'] = 0;
               $data['total_revenue'] = 0;
            }
        }

        $data['today_converted'] = $leadStats->converted_today;
        $data['delay'] = $followupStats->delay_total;
        $data['today_delay'] = $followupStats->delay_today;

        $freshLeadScope = clone $leadScope;
        $data['freshLead'] = $freshLeadScope->whereDoesntHave('Followup', fn($q) => $q->whereNotNull('lead_id'))->count();
        
        $todayFreshLeadScope = clone $leadScope;
        $data['today_freshLead'] = $todayFreshLeadScope->whereDate('created_at', $today)
            ->whereDoesntHave('Followup', fn($q) => $q->whereNotNull('lead_id'))->count();

        $data['today_total_reject'] = $followupStats->today_reject_wrong_info;
        $data['reject_wrong_info_count'] = $followupStats->reject_wrong_info;
        $data['reject_other_company_count'] = $followupStats->reject_other;
        $data['reject_not_intersted_count'] = $followupStats->reject_not_interested;
        $data['total_reject'] = $followupStats->total_reject;
        
        $data['followupPaymentToday'] = $followupStats->payment_tomorrow;
        $data['cold_clients'] = $followupStats->cold;
        $data['today_cold_clients'] = $followupStats->cold_today;
        $data['followupPending'] = $followupStats->pending;
        $data['followupCompleted'] = $followupStats->completed;
        $data['followupOther'] = $followupStats->other;
        $data['followupInterested'] = $followupStats->interested;

        return $data;
    }

    private function retrieveUsers($user)
    {
        $userRole = $user->roles()->pluck('name')->toArray();
        if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole)) {
            return User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Marketing-Manager', 'BDE', 'Business Development Intern']);
            })->get();
        }
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['BDE', 'Business Development Intern']);
        })->get();
    }

    private function initializeLeadCounts()
    {
        return [
            'today_leads' => 0, 'month_leads' => 0, 'year_leads' => 0, 'convert_leads' => 0,
            'fresh_lead' => 0, 'total_leads' => 0, 'today_followup' => 0, 'total_followup' => 0,
            'today_proposal' => 0, 'total_proposal' => 0, 'today_fresh' => 0, 'followup_today' => 0,
            'followup_completed_today' => 0, 'total_amount' => 0, 'total_revanue' => 0,
            'today_converted' => 0, 'converted_completed_today' => 0, 'delay' => 0,
            'freshLead' => 0, 'today_freshLead' => 0, 'total_reject' => 0, 'today_total_reject' => 0,
            'cold_clients' => 0, 'today_cold_clients' => 0, 'followupCompleted' => 0, 'followupPending' => 0,
        ];
    }

    // ==========================================
    //  FILTER HELPER METHODS
    // ==========================================

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search') && $search = $request->input('search')['value']) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('country') && $request->country) {
            $query->where('country', $request->input('country'));
        }

        if ($request->has('lead_day')) {
            $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
        }

        if ($request->has('status') && isset($request->status)) {
            $status = $request->input('status');
            if ($status == 7) {
                $query->where('status', 1);
            } else {
                $query->where('lead_status', $status);
            }
        }

        if ($request->has('category') && $request->category) {
            $query->where('client_category', $request->input('category'));
        }

        if ($request->has('service') && $request->service) {
            $query->whereJsonContains('project_category', (string) $request->service);
        }

        if ($request->has('proposal')) {
            $this->applyProposalFilter($query, $request->input('proposal'), $request);
        }

        if ($request->has('quotation')) {
            $this->applyQuatitonFilter($query, $request->input('quotation'), $request);
        }

        if ($request->has('bde') && $request->bde) {
            $query->where('assigned_user_id', $request->input('bde'));
        }

        if ($request->has('followup')) {
            $this->applyFollowupFilter($query, $request->input('followup'), $request);
        }

        if ($request->has('lead_type') && $request->lead_type) {
            $this->applyButtonFilter($query, $request->input('lead_type'), $request);
        }

        if ($request->has('lead_sub_type') && $request->lead_sub_type) {
            $this->applyButtonFilter($query, $request->input('lead_sub_type'), $request);
        }

        if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
    }

    private function applyLeadDayFilter($query, $leadDay, Request $request)
    {
        switch ($leadDay) {
            case 'today': $query->whereDate('created_at', Carbon::today()); break;
            case 'month': $query->whereMonth('created_at', Carbon::now()->month); break;
            case 'year':  $query->whereYear('created_at', Carbon::now()->year); break;
            case 'custome':
                if ($request->from_date && $request->to_date) {
                    $query->whereBetween('created_at', [Carbon::parse($request->from_date), Carbon::parse($request->to_date)->endOfDay()]);
                }
                break;
        }
    }

    private function applyProposalFilter($query, $proposal, Request $request)
    {
        switch ($proposal) {
            case 'today': $query->whereDate('proposal_date', Carbon::today())->where('proposal', 1); break;
            case 'month': $query->whereMonth('proposal_date', Carbon::now()->month)->where('proposal', 1); break;
            case 'year':  $query->whereYear('proposal_date', Carbon::now()->year)->where('proposal', 1); break;
            case 'custome':
                if ($request->from_date && $request->to_date) {
                    $query->whereBetween('created_at', [Carbon::parse($request->from_date), Carbon::parse($request->to_date)->endOfDay()])->where('proposal', 1);
                }
                break;
        }
    }

    private function applyQuatitonFilter($query, $quotation, Request $request)
    {
        switch ($quotation) {
            case 'today': $query->whereDate('quotation_date', Carbon::today())->where('quotation', 1); break;
            case 'month': $query->whereMonth('quotation_date', Carbon::now()->month)->where('quotation', 1); break;
            case 'year':  $query->whereYear('quotation_date', Carbon::now()->year)->where('quotation', 1); break;
            case 'custome':
                if ($request->from_date && $request->to_date) {
                    $query->whereBetween('created_at', [Carbon::parse($request->from_date), Carbon::parse($request->to_date)->endOfDay()])->where('quotation', 1);
                }
                break;
        }
    }

    private function applyFollowupFilter($query, $followup, Request $request)
    {
        switch ($followup) {
            case 'today':
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', now()->toDateString()));
                break;
            case 'this_week':
                $query->whereHas('Followup', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]));
                break;
            case 'month':
                $query->whereHas('Followup', fn($q) => $q->whereMonth('created_at', now()->month));
                break;
            case 'today_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('next_date', now()->toDateString()));
                break;
            case 'today_converted':
                $query->whereDate('created_at', Carbon::today())->where('status', 1);
                break;
        }
    }

    private function applyButtonFilter($query, $type, Request $request)
    {
        if (!$type) return;
        switch ($type) {
            case 'fresh_lead':
                $query->doesntHave('Followup');
                break;
            case 'convert_leads':
                $query->where('status', 1);
                break;
            case 'today_fresh_lead':
                $query->whereDate('created_at', Carbon::today())->doesntHave('Followup');
                break;
            case 'today_invoice':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'all_followup':
                $query->whereHas('Followup', fn($q) => $q->whereNotIn('reason', ['Work with other company', 'Wrong Information', 'Not interested']));
                break;
            case 'today_created_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', now()->toDateString()));
                break;
            case 'today_complated_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', now()->toDateString())->where('is_completed', 1));
                break;
            case 'today_pending_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('next_date', now()->toDateString())->where(fn($sq) => $sq->whereNull('is_completed')->orWhere('is_completed', '!=', 1)));
                break;
            case 'today_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('next_date', now()->toDateString()));
                break;
            case 'yesterday_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', now()->subDay())->orWhereDate('next_date', now()->subDay()));
                break;
            case 'last_7_days_followup':
                $query->whereHas('Followup', fn($q) => $q->whereBetween('next_date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])->orWhereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]));
                break;
            case 'this_month_followup':
                $query->whereHas('Followup', fn($q) => $q->whereBetween('next_date', [now()->startOfMonth(), now()->endOfMonth()])->orWhereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]));
                break;
            case 'followup_pending':
                $query->whereHas('Followup', fn($q) => $q->where('is_completed', '!=', 1));
                break;
            case 'followup_completed':
                $query->whereHas('Followup', fn($q) => $q->where('is_completed', 1));
                break;
            case 'followup_other':
                $query->whereHas('Followup', fn($q) => $q->where('reason', "Other"));
                break;
            case 'followup_interested':
                $query->whereHas('Followup', fn($q) => $q->where('reason', "Interested"));
                break;
            case 'today_converted':
                $query->whereHas('Followup', fn($q) => $q->whereDate('next_date', now()->toDateString()));
                break;
            case 'today_reminder':
                $query->whereHas('Payment', fn($q) => $q->whereDate('next_billing_date', now()->toDateString()));
                break;
            case 'today_billing':
                $query->whereDate('billing_date', Carbon::today());
                break;
            case 'cold_clients':
                $query->whereHas('Followup', fn($q) => $q->whereIn('reason', ['call back later', 'Not pickup']));
                break;
            case 'today_cold_clients':
                $query->whereHas('Followup', fn($q) => $q->whereIn('reason', ['call back later', 'Not pickup'])->whereDate('next_date', Carbon::today()));
                break;
            case 'rejects':
                $query->whereHas('Followup', fn($q) => $q->whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested']));
                break;
            case 'today_reject':
                $query->whereHas('Followup', fn($q) => $q->whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested'])->whereDate('created_at', Carbon::today()));
                break;
            case 'reject_wrong_info':
                $query->whereHas('Followup', fn($q) => $q->where('reason', 'Wrong Information'));
                break;
            case 'reject_other_company':
                $query->whereHas('Followup', fn($q) => $q->where('reason', 'Work with other company'));
                break;
            case 'reject_not_intersted':
                $query->whereHas('Followup', fn($q) => $q->where('reason', 'Not interested'));
                break;
            case 'followup_payment_today':
                $query->whereHas('Followup', fn($q) => $q->where('reason', 'Payment Tomorrow'));
                break;
            case 'delay':
            case 'delay_1_days':
            case 'delay_2_days':
            case 'delay_3_days':
            case 'delay_4_days':
            case 'delay_5+_days+':
                $query->whereHas('Followup', fn($q) => $q->where('delay', 1)->orWhere('is_completed', '!=', 1));
                break;
            case 'today_delay':
                $query->whereHas('Followup', fn($q) => $q->where(fn($sq) => $sq->where('delay', 1)->orWhere('is_completed', '!=', 1))->where('next_date', Carbon::today()));
                break;
            default:
                $query->whereHas('Followup', fn($q) => $q->whereNotIn('reason', ['Wrong Information', 'Not interested', 'Work with other company']));
                break;
        }
    }

    // ==========================================
    //  RESTORED CRUD & UTILITY METHODS
    // ==========================================

    public function counts(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->endOfDay();
        $user = auth()->user();
        $count = [];

        if ($user && $user->hasRole(['BDE', 'Business Development Intern'])) {
            $count['leads'] = Lead::where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])->orwhereBetween('assigned_date', [$startDate, $endDate]);
            })->where('assigned_user_id', $user->id)->count();
            
            $count['proposals'] = Lead::whereDate('proposal_date', Carbon::today())->where('proposal', 1)->where('assigned_user_id', $user->id)->count();
            $count['quotation'] = Lead::whereDate('quotation_date', Carbon::today())->where('quotation', 1)->where('assigned_user_id', $user->id)->count();
            $count['delay'] = 0;
            $count['reject'] = FollowUp::whereHas('lead')->where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereIn('reason', ['Wrong Information', 'Not interested', 'Work with other company'])
                        ->whereBetween('created_at', [$startDate, $endDate]);
                })->distinct('lead_id')->count();
            $count['revenue'] = 0;
            $count['followups'] = Followup::whereHas('lead')->where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])->distinct('lead_id')->count();

        } else {
            $count['leads'] = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
            $count['followups'] = Followup::whereHas('lead')->whereBetween('created_at', [$startDate, $endDate])->select('lead_id')->distinct()->count();
            $count['proposals'] = Lead::whereBetween('proposal_date', [$startDate, $endDate])->where('proposal', 1)->count();
            $count['quotation'] = Lead::whereBetween('quotation_date', [$startDate, $endDate])->where('quotation', 1)->count();
            $count['revenue'] = 0;
            $count['delay'] = Followup::whereHas('lead')
                ->where(fn($q) => $q->where('delay', 1)->orWhere('is_completed', '!=', 1))
                ->whereBetween('next_date', [$startDate, $endDate])->distinct('lead_id')->count();
            $count['reject'] = Followup::whereHas('lead')->whereIn('reason', ['Wrong Information', 'Not interested', 'Work with other company'])
                ->whereBetween('created_at', [$startDate, $endDate])->select('lead_id')->distinct()->count();
        }
        return response()->json($count);
    }

    public function Status($id, $status)
    {
        $lead = Lead::find($id);
        if($lead) $lead->update(['lead_status' => $status]);
        return redirect()->back()->with('message', 'status Changed !!');
    }

    public function convert_leads(Request $request)
    {
        $query = Lead::where('status', 1)->where('user_id', auth()->user()->id);
        if ($request->has('client_name')) {
            $clientName = $request->client_name;
            $query->where(function ($q) use ($clientName) {
                $q->where('name', 'like', '%' . $clientName . '%')
                    ->orWhere('city', 'like', '%' . $clientName . '%')
                    ->orWhere('email', 'like', '%' . $clientName . '%')
                    ->orWhere('phone', 'like', '%' . $clientName . '%');
            });
        }
        if ($request->has('lead_day')) {
            $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
        }
        $leads = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.crm.my_convert', compact('leads'));
    }

    public function PrposalService(Request $request, $leadId, $id = null)
    {
        if ($request->isMethod('get')) {
            if ($id) {
                $lead = User::findorfail($leadId);
                $service = Work::where('client_id', $leadId)->get();
            } else {
                $lead = Lead::with('category')->findorfail($leadId);
                $service = Work::where('lead_id', $leadId)->get();
            }
            return view('admin.crm.prposal.service', compact('lead', 'service', 'id'));
        } elseif ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'work_name' => 'required',
                'work_quality' => 'required|numeric',
                'work_price' => 'required|numeric',
                'work_type' => 'required',
                'currency' => 'required|',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $work = new Work();
            $work->work_name = $request->work_name;
            $work->work_quality = $request->work_quality;
            $work->work_price = $request->work_price;
            $work->work_type = $request->work_type;
            $work->currency = $request->currency;
            if ($id == 1) {
                $work->client_id = $leadId;
            } else {
                $work->lead_id = $leadId;
            }
            $work->save();
            return redirect()->back()->with('message', 'Service Added successfully.');
        }
    }

    public function PrposalServiceUpdate(Request $request, $workId, $leadId, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'work_name' => 'required',
            'work_quality' => 'required|numeric',
            'work_price' => 'required|numeric',
            'work_type' => 'required',
            'currency' => 'required|',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $work = Work::findorfail($workId);
        if ($work) {
            $work->update($request->only(['work_name', 'work_quality', 'work_price', 'work_type', 'currency']));
            if ($id == 1) {
                $work->client_id = $leadId;
            } else {
                $work->lead_id = $leadId;
            }
            $work->save();
            return redirect()->back()->with('message', 'Service Update successfully.');
        } else {
            return abort(404, 'Work not found.');
        }
    }

    public function PrposalInvoice(Request $request, $leadId, $id = null)
    {
        $services = $id ? Work::where('client_id', $leadId)->get() : Work::where('lead_id', $leadId)->get();
        if ($request->isMethod('get')) {
            $amount = isset($services) ? $services->sum('work_price') : 0;
            $lead = $id ? User::with('service')->find($leadId) : Lead::with('service')->find($leadId);
            $invoice = $id ? ProjectInvoice::where('client_id', $leadId)->first() : ProjectInvoice::where('lead_id', $leadId)->first();
            $offices = Office::orderBy('name', 'asc')->get();
            return view('admin.crm.prposal.invoice', compact('lead', 'invoice', 'amount', 'offices', 'id'));
        } elseif ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'bank_details' => 'required|numeric',
                'discount' => 'nullable',
                'office' => 'required',
                'total_amount' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
            $invoice = $request->invoice_id ? ProjectInvoice::find($request->invoice_id) : new ProjectInvoice();
            $invoice->bank = $request->bank_details;
            $invoice->gst = $request->gst;
            $invoice->client_gst_no = $request->gst_no;
            $invoice->user_id = auth()->user()->id;
            $invoice->office = $request->office;
            // $invoice->service_id = $service->id; // Logic check: $service undefined in this scope in original code
            $amount = $request->total_amount;
            $discount = $request->discount;
            $gstAmount = $amount * $request->gst / 100;
            $totalAmount = ($amount + $gstAmount) - $discount;
            $invoice->subtotal_amount = $amount;
            $invoice->discount = $discount;
            $invoice->gst_amount = $gstAmount;
            $invoice->total_amount = $totalAmount;
            if ($id) {
                $invoice->client_id = $leadId;
            } else {
                $invoice->lead_id = $leadId;
            }
            $invoice->save();
            $url = route('crm.prposel.mail.view', ['leadId' => $invoice->id] + ($id ? ['id' => $id] : []));
            return $this->success('created', 'send mail', $url);
        }
    }

    public function ConvertLeads(Request $request)
    {
        $query = Lead::with('category', 'totalAmount', 'user', 'services')->whereNull('client_id')->where('status', '=', 1);
        if ($request->has('client_name')) {
            $clientName = $request->client_name;
            $query->where(function ($q) use ($clientName) {
                $q->where('name', 'like', '%' . $clientName . '%')
                    ->orWhere('city', 'like', '%' . $clientName . '%')
                    ->orWhere('email', 'like', '%' . $clientName . '%')
                    ->orWhere('phone', 'like', '%' . $clientName . '%');
            });
        }
        if ($request->has('lead_day')) {
            $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
        }
        $leads = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.crm.convert', compact('leads'));
    }

    public function payment(Request $request, $leadId, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'mode' => 'required|string',
            'receipt_number' => 'required|numeric',
            'desopite_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'remark' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $lead = User::with('prposal')->findorfail($leadId);
            $totalAmout = TotalAmount::where('client_id', $leadId)->first();
        } else {
            $lead = Lead::with('prposal')->findorfail($leadId);
            $totalAmout = TotalAmount::where('lead_id', $leadId)->first();
        }
        if ($lead && $totalAmout) {
            $data = $request->all();
            $data['mode'] = $request->mode;
            $data['amount'] = $request->amount;
            $data['pending_amount'] = ($totalAmout->total_amount - $request->amount);
            $data['invoice_id'] = $lead->prposal->id;
            if ($id) {
                $data['client_id'] = $leadId;
            } else {
                $data['lead_id'] = $leadId;
            }
            $data['payment_status'] = 'Advanced';
            $timePart = $request->time ? $request->time : Carbon::now()->format('H:i:s');
            $datePart = date('Y-m-d', strtotime($request->desopite_date));
            $data['desopite_date'] = $datePart . ' ' . $timePart;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $storagePath = "images/" . date('Y') . "/" . date('m') . "/";
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($storagePath, $fileName);
                $data['image'] = $storagePath . $fileName;
            }
            $response = Payment::create($data);
            if ($response) {
                if ($id) {
                    $lead->client_status = 1;
                } else {
                    $lead->status = 1;
                }
                $lead->save();
                $totalAmout->pay = $response->amount;
                $totalAmout->balance = $totalAmout->total_amount - $response->amount;
                $totalAmout->save();
            }
            if ($id) {
                $url = route('crm.upsale.index');
            } else {
                $url = route('crm.index');
            }
            return $this->success('created', 'Payment Successfully Done', $url);
        }
        abort(503);
    }

    public function upsale(Request $request, $id)
    {
        $client = User::findorfail($id);
        $offices = Office::get();
        return view('admin.crm.upsale', compact('client', 'offices'));
    }

    public function createFreshInvoice(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|numeric',
                'billing_date' => 'required',
                'office' => 'required|numeric',
                'gst' => 'required|numeric',
                'bank_details' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'work_name.*' => 'required|string',
                'quantity.*' => 'required|numeric',
                'price.*' => 'required|numeric',
                'work_type.*' => 'required|string',
                'subtotal_value' => 'required|numeric',
                'gst_value' => 'required|numeric',
                'total_value' => 'required|numeric',
                'currency' => 'required',
            ]);

            if ($request->invoice_id) {
                $invoice = ProjectInvoice::findOrFail($request->invoice_id);
                $invoice->update([
                    'lead_id' => $request->client_id,
                    'office' => $request->office,
                    'bank' => $request->bank_details,
                    'billing_date' => $request->billing_date,
                    'discount' => $request->discount ?? 0,
                    'gst' => $request->gst,
                    'client_gst_no' => $request->client_gst_no,
                    'total_amount' => $request->total_value,
                    'balance' => $request->total_value,
                    'gst_amount' => $request->gst_value,
                    'currency' => $request->currency,
                    'subtotal_amount' => $request->subtotal_value,
                    'user_id' => auth()->user()->id,
                ]);
                $currentWorkIds = Work::where('invoice_id', $invoice->id)->pluck('id')->toArray();
                foreach ($request->work_id as $key => $workId) {
                    $work = Work::where('invoice_id', $invoice->id)->where('id', $workId)->first();
                    if ($work) {
                        $work->update([
                            'work_name' => $request->work_name[$key],
                            'work_quality' => $request->quantity[$key],
                            'work_price' => $request->price[$key],
                            'work_type' => $request->work_type[$key],
                        ]);
                    } else {
                        Work::create([
                            'invoice_id' => $invoice->id,
                            'work_name' => $request->work_name[$key],
                            'work_quality' => $request->quantity[$key],
                            'work_price' => $request->price[$key],
                            'work_type' => $request->work_type[$key],
                        ]);
                    }
                    $currentWorkIds = array_diff($currentWorkIds, [$workId]);
                }
                if (count($currentWorkIds) > 0) {
                    Work::whereIn('id', $currentWorkIds)->delete();
                }
            } else {
                $invoice = ProjectInvoice::create([
                    'lead_id' => $request->client_id,
                    'office' => $request->office,
                    'bank' => $request->bank_details,
                    'billing_date' => $request->billing_date,
                    'discount' => $request->discount ?? 0,
                    'gst' => $request->gst,
                    'client_gst_no' => $request->client_gst_no,
                    'total_amount' => $request->total_value,
                    'balance' => $request->total_value,
                    'gst_amount' => $request->gst_value,
                    'currency' => $request->currency,
                    'subtotal_amount' => $request->subtotal_value,
                    'user_id' => auth()->user()->id,
                ]);
                foreach ($request->work_name as $key => $workName) {
                    Work::create([
                        'invoice_id' => $invoice->id,
                        'work_name' => $workName,
                        'work_quality' => $request->quantity[$key],
                        'work_price' => $request->price[$key],
                        'work_type' => $request->work_type[$key],
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong!', 'message' => $e->getMessage()], 500);
        }
        return redirect()->route('crm.prposel.mail.view', ['leadId' => $invoice->id]);
    }

    public function createInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'billing_date' => 'required|date',
            'gst' => 'required',
            'bank_details' => 'required',
            'office' => 'required',
            'work_name.*' => 'required',
            'quantity.*' => 'required',
            'price.*' => 'required|numeric|min:1',
            'work_type.*' => 'required',
            'discount' => 'required',
            'subtotal_value' => 'required',
            'gst_value' => 'required',
            'total_value' => 'required',
            'currency' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = [
            'client_id' => $request->client_id,
            'billing_date' => date('Y-m-d', strtotime($request->billing_date)),
            'office' => $request->office,
            'bank' => $request->bank_details,
            'gst' => $request->gst,
            'discount' => $request->discount,
            'subtotal_amount' => $request->subtotal_value,
            'gst_amount' => $request->gst_value,
            'total_amount' => $request->total_value,
            'balance' => $request->total_value,
            'currency' => $request->currency,
        ];
        $invoice = ProjectInvoice::create($data);
        if ($invoice) {
            foreach ($request->work_name as $key => $workName) {
                Work::create([
                    'invoice_id' => $invoice->id,
                    'work_name' => $workName,
                    'work_quality' => $request->quantity[$key],
                    'work_price' => $request->price[$key],
                    'work_type' => $request->work_type[$key],
                ]);
            }
            return redirect()->route('crm.prposel.mail.view', ['leadId' => $invoice->id, 'id' => 1]);
        }
        return response()->json(['error' => 'Invoice creation failed.']);
    }

    public function viewMail($leadId, $id = null)
    {
        $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->find($leadId);
        if (!$invoice) {
            $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->where('lead_id', $leadId)->latest()->first();
        }
        return view('admin.crm.prposal.mail_preview', compact('invoice', 'id'));
    }

    public function mail(Request $request, $invoiceId, $id = null)
    {
        $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->findorfail($invoiceId);
        if (empty($request->send_mail) && empty($request->send_whatsapp)) {
            $validator = Validator::make($request->all(), [
                'send_mail' => 'required_without_all:send_whatsapp',
                'send_whatsapp' => 'required_without_all:send_mail',
            ], [
                'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
        }
        $fileUrl = null;
        if ($request->send_custome_pdf == 1) {
            $validator = Validator::make($request->all(), ['custome_pdf' => 'required|mimes:pdf|max:2048']);
            if ($validator->fails()) return response()->json(['errors' => $validator->errors()]);
            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "Proposals/custome/{$currentYear}/{$currentMonth}";
            $dateTime = date('Ymd_His');
            if ($file = $request->file('custome_pdf')) {
                $fileName = $id == 1 ? $invoice->client->email : $invoice->lead->email . '_proposal_' . $dateTime . '.' . $file->getClientOriginalExtension();
                if (!file_exists($directoryPath)) mkdir($directoryPath, 0755, true);
                $filePath = $directoryPath . '/' . $fileName;
                $file->move($directoryPath, $fileName);
                $fileUrl = asset($filePath);
            }
        }
        $html = view('admin.crm.prposal.mail', compact('invoice', 'id'))->render();
        if (empty($html)) return response()->json(['error' => 'HTML content is empty']);
        try {
            $pdf = PDF::loadHTML($html);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        $pdfPath1 = "Proposals/Adxventure.pdf";
        $currentYear = date('Y');
        $currentMonth = date('m');
        $directoryPath = "Proposals/pdf/{$currentYear}/{$currentMonth}";
        $dateTime = date('Ymd_His');
        $pdfFileName = ($id == 1 ? $invoice->client->name : $invoice->lead->name) . '_proposal_' . $dateTime . '.pdf';
        $pdfPath = $directoryPath . '/' . $pdfFileName;
        if (!file_exists($directoryPath)) mkdir($directoryPath, 0755, true);
        $pdf->save($pdfPath);
        $invoice->pdf = $pdfPath;
        $invoice->invoice_no = sprintf('#00%02d/%s/%d-%02d', $invoice->id, date('M', strtotime($invoice->created_at)), date('Y', strtotime($invoice->created_at)), date('y', strtotime($invoice->created_at)) + 1);
        $invoice->save();
        if ($id != 1) {
            $lead = Lead::findOrFail($invoice->lead->id);
            $lead->quotation = 1;
            $lead->quotation_date = Carbon::now();
            $lead->save();
        }
        if ($request->has('send_mail')) {
            $to = $id == 1 ? $invoice->client->email : $invoice->lead->email;
            $name = strtoupper($id == 1 ? $invoice->client->name : $invoice->lead->name);
            $subject = 'Adxventure Billing Invoice';
            $message = "Welcome to <strong>{$name}</strong>,<br><br>Excited to Start Our Journey Together!<br><br>";
            $files = [$pdfPath, $pdfPath1, $fileUrl];
            $boundary = md5(uniqid(time()));
            $headers = "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"{$boundary}\"\r\nFrom: info@adxventure.com\r\n";
            $body = "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 7bit\r\n\r\n" . $message . "\r\n";
            foreach ($files as $filePath) {
                if ($filePath) {
                    $fileName = basename($filePath);
                    $fileContent = file_get_contents($filePath);
                    $fileContentEncoded = chunk_split(base64_encode($fileContent));
                    $body .= "--{$boundary}\r\nContent-Type: application/pdf; name=\"{$fileName}\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n" . $fileContentEncoded . "\r\n";
                }
            }
            $body .= "--{$boundary}--";
            mail($to, $subject, $body, $headers);
        }
        if ($request->has('send_whatsapp')) {
            $phone = $id == 1 ? '91' . $invoice->client->phone_no : str_replace('-', '', $invoice->lead->phone);
            $name = $id == 1 ? $invoice->client->name :  $invoice->lead->name;
            $api = Api::first();
            $params = ['recipient' => $phone, 'apikey' =>  $api->key, 'text' => "Hello {$name}, Greetings from Adxventure. Please find the attached Quotation. Thank you."];
            $apiUrl = $api->url;
            foreach ([$pdfPath, $pdfPath1, $fileUrl] as $file) {
                if ($file) {
                    $params['file'] = asset($file);
                    $queryString = http_build_query($params);
                    Http::get("{$apiUrl}?{$queryString}");
                }
            }
        }
        return $id == 1 ? $this->success('Success', '', url('invoice')) : $this->success('Success', '', url('crm/leads'));
    }

    public function AllUpsale()
    {
        $leads = User::with('services', 'category', 'totalAmount')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->get();
        return view('admin.crm.upsale', compact('leads'));
    }

    public function paymentShow($leadId, $id = null)
    {
        if ($id) {
            $data = Payment::where('client_id', $leadId)->get();
            $lead = User::findorfail($leadId);
            $total = TotalAmount::where('client_id', $leadId)->first();
        } else {
            $data = Payment::where('lead_id', $leadId)->get();
            $lead = Lead::findorfail($leadId);
            $total = TotalAmount::where('lead_id', $leadId)->first();
        }
        return view('admin.crm.payment', compact('data', 'lead', 'total'));
    }

    public function bulkUpdate(Request $request)
    {
        $status = $request->input('action');
        $leadIds = $request->input('selectedLeads', []);
        if ($status && !empty($leadIds)) {
            $leads = Lead::whereIn('id', $leadIds)->get();
            $leadsToUpdateIds = $leads->filter(fn($lead) => $lead->status != 1)->pluck('id')->toArray();
            if (!empty($leadsToUpdateIds)) {
                Lead::whereIn('id', $leadsToUpdateIds)->update(['lead_status' => $status]);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function leadAssigned(Request $request)
    {
        $userId = $request->input('assignd_user');
        $leadIds = $request->input('leads', []);
        if ($userId && !empty($leadIds)) {
            foreach ($leadIds as $leadId) {
                $lead = Lead::find($leadId);
                if ($lead) {
                    $lead->assigned_user_id = $userId;
                    $lead->created_at = Carbon::today();
                    $lead->save();
                }
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function prposal($id, $status = null)
    {
        if ($status) {
            $lead = User::with('prposal')->findorfail($id);
            $pdfs = DB::table('prposal')->where('invoice_id', $lead->prposal->id)->orderBy('id', 'desc')->get();
        } else {
            $lead = Lead::with('prposal')->findorfail($id);
            $pdfs = DB::table('prposal')->where('invoice_id', $lead->prposal->id)->orderBy('id', 'desc')->get();
        }
        return view('admin.crm.prposal', compact('lead', 'pdfs', 'status'));
    }

    public function converted_leads()
    {
        $leads = Lead::where('status', 1)->whereNotNull('client_id')->get();
        if ($leads->isEmpty()) return abort(404, 'Leads not found');
        $projects = collect();
        foreach ($leads as $lead) {
            $clientProjects = Projects::with('client', 'category', 'work')->where('client_id', $lead->client_id)->get();
            $projects = $projects->merge($clientProjects);
        }
        return view('admin.crm.converted', compact('projects'));
    }

    public function today_proposal()
    {
        $leads = Lead::where('status', 1)->where('created_at', Carbon::today())->get();
        return view('admin.crm.today_proposal', compact('leads'));
    }

    public function today_followup(Request $request)
    {
        $followups = Followup::with('lead', 'user')->whereNotNull('lead_id');
        if ($request->has('bde')) $followups->where('user_id', $request->bde);
        if ($request->has('day')) {
            switch ($request->day) {
                case 'today': $followups->whereDate('created_at', Carbon::today()); break;
                case 'month': $followups->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year); break;
                case 'year': $followups->whereYear('created_at', Carbon::now()->year); break;
                case 'custome': if ($request->has('from_date') && $request->has('to_date')) $followups->whereBetween('created_at', [$request->from_date, $request->to_date]); break;
            }
        }
        $followups = $followups->paginate(20);
        $users = $this->retrieveUsers(auth()->user());
        return view('admin.crm.today_followup', compact('followups', 'users'));
    }

    // Original had syntax error { { .. }, fixed here.
    public function today_report(Request $request)
    {
        // This method relies on variables ($tableData, etc) that were implicitly passed in original context 
        // usually via index(). To make it work as standalone AJAX if needed, we'd need to re-run logic.
        // Assuming it's meant to return JSON for AJAX based on filters:
        
        $user = auth()->user();
        $query = $this->initializeLeadQuery();
        $userRoleData = $this->handleRoleBasedLogic($user, $query); // Reuse logic
        
        return response()->json([
            'tableData' => $userRoleData['leads'], // Fixed from undefined $tableData
            'projectCategories' => ProjectCategory::all(),
            'users' => $this->retrieveUsers($user),
            'categories' => Category::all(),
            'services' => ProjectCategory::all(),
            // Pass back request inputs
            'client_name' => $request->input('client_name'),
            'lead_status' => $request->input('lead_status'),
            'date' => $request->input('date'),
            'category' => $request->input('category'),
            'service' => $request->input('service'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            // 'templates' => $templates, // Undefined in original scope, removed to prevent crash
            // 'messagetemplates' => $messagetemplates, // Undefined in original scope, removed
            'countries' => Country::select('id', 'nicename')->get(),
            // 'bdeReports' => $reports, // Undefined in original scope
        ]);
    }

    public function freshsale($id)
    {
        $client = Lead::findorfail($id);
        $offices = Office::orderBy('name', 'asc')->get();
        $invoice = ProjectInvoice::with('lead', 'services', 'Bank')->where('lead_id', $id)->first();
        return view('admin.crm.freshsale', compact('client', 'offices', 'invoice'));
    }

    public function offer_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_user' => 'required|numeric',
            'sendbywhatshapp' => 'required_without_all:sendbyemail',
            'sendbyemail' => 'required_without_all:sendbywhatshapp',
        ], ['required_without_all' => 'Please select at least one option: Mail or WhatsApp.']);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()]);

        try {
            $lead = Lead::findOrFail($request->message_user);
            $name = $lead->name;
            $phone = $lead->phone;
            $email = $lead->email;
            $subject = "Adxventure Portfolio";
            $message = "Dear " . $name . ",\nWe hope this message finds you well.\nThank you,\n Adxventure\n";
            $fileUrl = asset("portfolio/adxventure_portfolio.pdf");

            if ($request->has('sendbywhatshapp')) {
                if (!str_starts_with($phone, '+91')) {
                    $phone = explode('-', $phone);
                    $phone = '91' . ($phone[1] ?? $phone[0]);
                }
                $api = auth()->user()->api;
                $response = Http::get($api->url, [
                    'recipient' => $phone,
                    'apikey' => $api->key,
                    'text' => $message,
                    'file' => $fileUrl,
                ]);
                if (!$response->successful()) return response()->json(['error' => 'Failed to send message via WhatsApp.']);
            }

            if ($request->has('sendbyemail')) {
                // Simplified mail logic for brevity, assuming standard mail() works or use Laravel Mail
                $to = $email;
                $headers = "From: info@adxventure.com";
                mail($to, $subject, $message, $headers);
            }
            return response()->json(['success' => 'Portfolio sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function messages($id)
    {
        $messages = Message::where('lead_id', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.crm.meesages', compact('messages'));
    }

    public function cutome_proposal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proposal_user' => 'required|numeric',
            'sendbywhatshapp' => 'required_without_all:sendbyemail',
            'sendbyemail' => 'required_without_all:sendbywhatshapp',
            'proposal_type' => 'required|numeric',
        ], ['required_without_all' => 'Please select at least one option: Mail or WhatsApp.']);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()]);

        try {
            $lead = Lead::findOrFail($request->proposal_user);
            $phone = $lead->phone;
            if (!str_starts_with($phone, '+91')) {
                $phone = explode('-', $phone);
                $phone = '91' . ($phone[1] ?? $phone[0]);
            }
            $category = Category::findorFail($lead->client_category);
            $raw_message = $category->whatshapp_message;
            // Clean up message
            $formatted_message = strip_tags(str_ireplace(['<div>', '</div>', '<br>', '<br/>', '<br />', '</p>', '<p>'], "\n", $raw_message));
            $whatshapp_message = "Hello Sir/Ma'am,\nGreetings from Adxventure.\n" . trim($formatted_message) . "\nThank you,\nAdxventure\n+91-9149214580\nhttps://adxventure.com/";

            if ($request->proposal_type == 1 && $request->has('sendbywhatshapp')) {
                $api = auth()->user()->api;
                $response = Http::get($api->url, [
                    'file' => asset($category->image),
                    'text' => $whatshapp_message,
                    'apikey' => $api->key,
                    'recipient' => $phone,
                ]);
                if (!$response->successful()) return response()->json(['error' => 'Failed to send message via WhatsApp.']);
            } elseif ($request->proposal_type == 2 && $request->has('sendbywhatshapp')) {
                $api = auth()->user()->api;
                $response = Http::get($api->url, [
                    'recipient' => $phone,
                    'apikey' => $api->key,
                    'text' => $whatshapp_message,
                    'file' => asset($category->pdf),
                ]);
                if (!$response->successful()) return response()->json(['error' => 'Failed to send message via WhatsApp.']);
            }
            $lead->update(['proposal' => 1, 'proposal_date' => Carbon::now()]);
            return response()->json(['success' => 'Message sent successfully.']);
        } catch (\Exception $e) {
            return $e;
        }
    }

    // THE MISSING METHOD - RESTORED
    public function proposalType(Request $request)
    {
        $lead = Lead::findorfail($request->id);
        if ($lead) {
            $category = Category::findorFail($lead->client_category);
            $data = [
                'image' => asset($category->image),
                'pdf' => asset($category->pdf),
                'whatshapp_message' => $category->whatshapp_message,
                'email_message' => $category->email_message,
            ];
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Lead not found.']);
        }
    }

    public function api()
    {
        $api = Api::where('user_id', auth()->user()->id)->first();
        return view('admin.crm.api', compact('api'));
    }

    public function api_store(Request $request)
    {
        $validator = Validator::make($request->all(), ['number' => 'required|numeric|digits:10']);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()]);
        try {
            $response = Http::post('https://wabot.adxventure.com/api/user/create-api-key', ['userId' => "68886051d5c622185099371d", 'mobileNumber' => $request->number]);
            $data = $response->json();
            if ($data['status']) {
                Api::create(['name' => "whatshapp", 'user_id' => auth()->user()->id, 'url' => "http://wabot.adxventure.com/api/user/send-media-message", 'key' => $data['apiKey'], 'phone' => $request->number, 'trial_ends' => $data['trialEndsAt']]);
                $success_message = $data['message'];
            } else {
                $success_message = $data['message'];
            }
            return response()->json(['success' => $success_message]);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
        }
    }
}