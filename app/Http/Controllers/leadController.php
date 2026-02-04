<?php
namespace App\Http\Controllers;

use PDF;
use App\Client;

use League\Csv\Reader;
use League\Csv\Statement;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Models\{Invoice,User,Work,Payment,ProjectCategory,lead,Category,TotalAmount,Projects,Followup,Country,CustomRole,Template};

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


use Maatwebsite\Excel\Facades\Excel;    
use Maatwebsite\Excel\HeadingRowImport;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Redirect;

use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Imports\UsersImport;

use App\Exports\SampleExport;
use App\Exports\ServiceExport;
use App\Exports\CategoryExport;
use App\Exports\MultiSheetExport;


class leadController extends Controller
{
    public function create()
    {
        $services = ProjectCategory::orderBy('name', 'asc')->get(['name', 'id']);
        $categories = Category::orderBy('name', 'asc')->get(['name', 'category_id']);
        $countries = Country::orderBy('nicename', 'asc')->get(['id', 'nicename', 'phonecode']);
        
        $user = auth()->user(); // Access the stored user property
    
        if ($user->hasRole(['Super-Admin', 'Admin'])) {
            // Get the roles you want to filter by
            $roleIds = CustomRole::whereIn('name', ['Marketing-Manager', 'BDE', 'Business Development Intern'])->pluck('id');
            // Get users with those roles
            $users = User::whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            })->get();
        } elseif ($user->hasRole('Marketing-Manager')) {
            // Get the roles you want to filter by
            $roleIds = CustomRole::whereIn('name', ['BDE', 'Business Development Intern'])->pluck('id');
            // Get users with those roles
            $users = User::whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            })->get();
        } else {
            $users = collect();
        }
    
        return view('admin.crm.create', compact('services', 'categories', 'countries', 'users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();  // Access the stored user property
    
        $request->merge([
            'full_phone' => $request->phone_code . '-' . $request->phone
        ]);
        $rules = [
            'name' => 'required|string|max:50',
            'phone' => 'required|numeric|digits_between:8,15',
            'full_phone' => 'required|unique:lead,phone|unique:users,phone_no',
            'email' => 'nullable|email|unique:lead,email|unique:users,email',
            'client_category' => 'required|numeric|exists:category,category_id',
            'lead_status' => 'required|numeric',
            'country' => 'numeric|exists:countries,id',
            'phone_code' => 'numeric|exists:countries,phonecode',
            'lead_source' => 'required|numeric',
            'project_category' => 'required|array',
            'project_category.*' => 'required|integer|exists:project_category,id',
            'city' => 'max:20'
        ];
    
        $messages = [
            'full_phone.unique' => 'This phone number already exists.',
            'phone.digits_between' => 'The phone field must be between 8 and 15 digits.',
            'email.unique' => 'This email address already exists.',
        ];
    
        if ($request->lead_source == 3) {
            $rules['ref_name'] = 'required|string';
    
            if ($user->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])) {
                $rules['assign_user'] = 'numeric';
            }
        }
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $projectCategories = $request->project_category;
    
        $lead = Lead::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone_code.'-'.$request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'city' => $request->city,
            'country' => $request->country,
            'lead_source' => $request->lead_source,
            'project_category' => json_encode($projectCategories),
            'lead_status' => $request->lead_status,
            'client_category' => $request->client_category,
            'user_id' => $user->id,
            'assigned_user_id' => $user->id,
            'assigned_by' => $request->assign_user ?? $user->id,
        ]);

        $url = url('crm/create/leads');
        return $this->success('created', 'Leads', $url);
    }

    public function update(Request $request, $id, $status = null)
    {
        // dd($request->all());
        $user = auth()->user();  // Access the stored user property
    
        // $rules = [
        //     'name' => 'required|string|max:50',
        //     'phone' => [
        //         'required',
        //         'numeric',
        //         'min:10000000',
        //         'max:10000000000'
        //     ],
        //     'email' => [
        //         'nullable',
        //         'email'
        //     ],
        //     'client_category' => 'required|numeric|exists:category,category_id',
        //     'country' => 'nullable|numeric|exists:countries,id',
        //     'phone_code' => 'nullable|numeric|exists:countries,phonecode',
        //     'project_category' => 'required|array',
        //     'project_category.*' => 'required|integer|exists:project_category,id',
        //     'city' => 'nullable|max:20',
           
        // ];
    
    
        $rules = [
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s\\-]+$/'],
            'company_name' =>['nullable','string','max:100','regex:/^[A-Za-z\s\\-]+$/'],
            'phone' => [
                'required',
                'numeric',
                'digits_between:8,11',
                Rule::unique('lead', 'phone')->ignore($id),
                Rule::unique('users', 'phone_no')->ignore($id)
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('lead', 'email')->ignore($id),
                Rule::unique('users', 'email')->ignore($id)
            ],
            'client_category' => 'required|numeric|exists:category,category_id',
            'country' => 'nullable|numeric|exists:countries,id',
            'phone_code' => 'nullable|numeric|exists:countries,phonecode',
            'project_category' => 'required|array',
            'project_category.*' => 'required|integer|exists:project_category,id',
            'city' => 'nullable|max:20',
        ];
    
        $messages = [
            'phone.unique' => 'This phone number already exists.',
            'phone.min' => 'The phone field must be at least 8 digits.',
            'phone.max' => 'The phone field must be no more than 15 digits.',
            'email.unique' => 'This email address already exists.',
        ];
    
        if ($request->lead_source == 3) {
            // $rules['ref_name'] = 'required|string';
    
            if ($user->hasRole(['Super-Admin', 'Admin', 'Marketing-Manager'])) {
                $rules['assign_user'] = 'nullable|numeric';
            }
        }

        if($request->client != 1){
            $rules['lead_status'] = 'required|numeric';
            $rules['lead_source'] = 'required|numeric';
        }
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $projectCategories = $request->project_category ?? [];
        $lead = Lead::findOrFail($id);
        
        $lead->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone_code . '-' . $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'website' => $request->website,
            'domian_expire' => $request->domian_expire,
            'country' => $request->country,
            'project_category' => json_encode($projectCategories),
            'client_category' => $request->client_category,
            'user_id' => $user->id,
            'assigned_by' => $user->id,
            'assigned_user_id' => $request->assign_user ?? $user->id,
            'ref_name' => ($request->lead_source == 3) ? ($request->ref_name ?? '') : '',
            'lead_status' => $request->lead_status ?? '0',
            'lead_source' => $request->lead_source,
        ]);
    
        $url = $status ? url('crm/leads') : route('crm.freshsale', ['leadId' => $id]);
        return $this->success('updated', 'Leads', $url);
    }


    // public function downloadSample()
    // {
    //     // Fetch user data based on roles
    //     $userData = function() {
    //         if (auth()->user()->hasRole(['Super-Admin', 'Admin'])) {
    //             $roleIds = CustomRole::whereIn('name', ['Marketing-Manager', 'BDE', 'Business Development Intern'])->pluck('id');
    //         } elseif (auth()->user()->hasRole('Marketing-Manager')) {
    //             $roleIds = CustomRole::whereIn('name', ['BDE', 'Business Development Intern'])->pluck('id');
    //         } else {
    //             return [];
    //         }

    //         // Fetch users with roles
    //         $users = User::whereHas('roles', function ($query) use ($roleIds) {
    //             $query->whereIn('role_id', $roleIds);
    //         })->get(['id', 'name']);

    //         // Map users to include roles as a comma-separated string
    //         return $users->map(function ($user) {
    //             $roleNames = $user->roles->pluck('name')->implode(', ');
    //             return [
    //                 'id' => $user->id,
    //                 'name' => $user->name,
    //                 'roles' => $roleNames
    //             ];
    //         })->toArray();
    //     };


    //     // Define the sheets data
    //     $sheetsData = [
    //         'Leads' => [
    //             'headers' => ['Name', 'Email(optional)', 'Phone_Code', 'Phone_no', 'Client_category', 'Service', 'Country', 'City(optional)', 'Website(optional)', 'lead_status(1=>Hot, 2=>Warm, 3=>Cold)', 'Lead_source(1=>Website, 2=>Social Media, 3=>Reference, 4=>Bulk Lead)', 'Reference Name(if lead source reference)', 'Assigned_to(Assigned User id)(optional)'],
    //             'data' => [['manjeet', 'manjeet@gmail.com', 91, 9997294527, 1, '1,2', 1, 'Dehradun', 'demo.com', 1, 4, 'ref_name', 101]],
    //             'exportClass' => \App\Exports\DynamicSheet::class,
    //             'title' => 'Leads'
    //         ],
    //         'Service' => [
    //             'exportClass' => \App\Exports\ServiceExport::class,
    //             'headers' => ['ID', 'Name'],
    //             'data' => ProjectCategory::get(['id', 'name'])->toArray(),
    //             'title' => 'Service'
    //         ],
    //         'Client_category' => [
    //             'exportClass' => \App\Exports\CategoryExport::class,
    //             'headers' => ['ID', 'Name'],
    //             'data' => Category::get(['category_id', 'name'])->toArray(),
    //             'title' => 'Client Category'
    //         ],
    //         'country' => [
    //             'exportClass' => \App\Exports\CountryExport::class,
    //             'headers' => ['ID', 'Name', 'PhoneCode'],
    //             'data' => Country::get(['id', 'name','phonecode'])->toArray(),
    //             'title' => 'Countries'
    //         ],
    //         'Employee' => [
    //             'exportClass' => \App\Exports\UserExport::class,
    //             'headers' => ['ID', 'Name','Role'],
    //             'data' => $userData(),
    //             'title' => 'Employee'
    //         ]
    //     ];

    //     $fileName = 'sample.xlsx';
    //     return Excel::download(new MultiSheetExport($sheetsData), $fileName);
    // }


    public function downloadSample()
    {
        return Excel::download(new SampleExport, 'sample.xlsx');
    }


    /**
     * 
     */
    public function uploadCsv(Request $request)
    {
        $user = auth()->user();
        $validatedData = $request->validate([
            'country'           => 'required|numeric|exists:countries,id',
            'phone_code'        => 'required|numeric|exists:countries,phonecode',
            'client_category'   => 'required|numeric|exists:category,category_id',
            'lead_status'       => 'required|numeric',
            'project_category'  => 'required|array',
            'project_category.*'=> 'required|integer|exists:project_category,id',
            'file'              => 'required|file',
            'city'              => 'required',
        ]);


        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            // Convert to MessageBag compatible format
            $flatErrors = [];
        
            foreach ($import->errors as $error) {
                if (isset($error['row']) && isset($error['errors'])) {
                    foreach ($error['errors'] as $msg) {
                        $flatErrors[] = "Row {$error['row']}: {$msg}";
                    }
                } else {
                    $flatErrors[] = is_string($error) ? $error : json_encode($error);
                }
            }
        
            return redirect()->back()->with('custom_errors', $import->errors);
        }
        
   
        foreach ($import->preparedData as $data) {
            $phoneFormatted = $request->phone_code . '-' . $data['phone'];
        
            // Avoid inserting duplicate phone/email even if missed by the import class
            // if (DB::table('lead')->where('email', $data['email'])->orWhere('phone', $phoneFormatted)->exists()) {
            //     continue;
            // }
          
            try{
                $dataToInsert = [
                    'name'             => $data['name'],
                    'phone'            => $phoneFormatted,
                    'email'            => $data['email'],
                    'website'          => $data['website'],
                    'country'          => $request->country,
                    'city'             => $request->city,
                    'lead_source'      => 4,
                    'project_category' => json_encode($request->project_category),
                    'lead_status'      => $request->lead_status,
                    'client_category'  => $request->client_category,
                    'user_id'          => $user->id,
                    'assigned_by' => $user->id,
                    'assigned_user_id'      => $request->assign_user ?? $user->id,
                ];
            
                DB::table('lead')->insert($dataToInsert);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
                Log::error('Error inserting lead data: ' . $e->getMessage());
            }

           
        
        
        }
   
        return redirect()->back()->with('success', 'File imported successfully!');
    }



    public function delete(Request $request){
        $users = $request->input('users');
        if ($users) {
            Lead::whereIn('id', $users)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function sendMail(Request $request)
    {
        $users = $request->input('users');
        $sentCount = 0;
    
        foreach ($users as $userId) {
            $lead = Lead::findOrFail($userId);
    
            // Render HTML view (example HTML content)
            $html = "<h1>Manjeet</h1>";
    
            // Debugging: Check the rendered HTML content
            if (empty($html)) {
                dd('HTML content is empty');
            }
    
            // Email details
            $to = 'manjeetchand01@gmail.com';
            $subject = 'Adxventure Billing Invoice';
            $name = strtoupper($lead->name);
            $message = 'Dear <strong>' . $name . '</strong>,<br><br>' .
                       'Please find attached the invoice for your recent work.<br><br>' .
                       'Thank you for your business.';
    
            // Headers
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: info@adxventure.com\r\n";
    
            // Email Body
            $body = $message;
    
            // Send email
            if (mail($to, $subject, $body, $headers)) {
                $sentCount++;
            }
        }
    
        return response()->json(['success' => true, 'count' => $sentCount]);
    }
    


    public function report(){
        $data = User::whereHas('roles', function ($query) {
            $query->where('name', 'BDE');
        })->with('lead', 'followup')->get();

        return view('admin.user.bde-report', compact('data'));
    }



    public function edit(Request $request){
       if($request->type === 'edit-lead'){
            $lead = Lead::findorfail($request->id);
            return response()->json(['success' => true, 'lead' => $lead]);
       }
    }


    private function initializeLeadQuery()
    {
        return Lead::with('category', 'totalAmount','Followup','countries');
    }

    public function leadText(Request $request)
    {
        // dd($request->all());
        // Retrieve necessary data
        $users = $this->retrieveUsers(auth()->user());
        $projectCategories = ProjectCategory::all();
        $bdeReports = $this->reports(auth()->user());
        $categories = Category::with('lead')->orderBy('name', 'asc')->get();
        $services = ProjectCategory::with('lead')->orderBy('name', 'asc')->get();
        $countries = Country::orderBy('nicename', 'asc')->get(['id', 'nicename', 'phonecode']);
        $messagetemplates = Template::where('category', 'common')->where('type', 3)->orderBy('title', 'asc')->get();
        // Initialize query and apply filters
        $query = $this->initializeLeadQuery();
        $leadCounts = $this->initializeLeadCounts();
        $userRoleData = $this->handleRoleBasedLogic(auth()->user(), $query);
        $this->applyFilters($query, $request);
    
        // Apply additional filter based on 'filter' button type
        $filter = $request->input('filter');
        if ($filter){
            switch ($filter) {
                case 'all_lead':
                    // No additional filtering needed
                    break;
                case 'fresh_lead':
                    $query->whereDoesntHave('Followup', function ($q) {
                            $q->whereNotNull('lead_id');
                        });
                    break;
                case 'today_fresh_lead':
                    $query->whereDate('created_at', Carbon::today())
                            ->whereDoesntHave('Followup', function ($q) {
                                $q->whereNotNull('lead_id');
                            });
                    break;
                case 'today_invoice':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'today_followup':
                    $query->whereHas('Followup', function($q) {
                        $q->whereDate('next_date', now()->toDateString());
                    });
                    break;
                case 'followup_pending':
                    $query->whereHas('Followup', function($q) {
                        $q->where('is_completed','!=',1);
                    });
                    break;
                case 'followup_completed':
                    $query->whereHas('Followup', function($q) {
                        $q->where('is_completed',1);
                    });
                    break;
                case 'today_converted':
                    $query->whereHas('Followup', function($q) {
                        $q->whereDate('next_date', now()->toDateString());
                    });
                    break;
                case 'today_reminder':
                    $query->whereHas('Payment', function($q) {
                        $q->whereDate('next_billing_date', now()->toDateString());
                    });
                    break;
                case 'today_billing':
                    $query->whereDate('billing_date', Carbon::today());
                    break;
                case 'cold_clients':
                    $query->whereHas('Followup', function($q) {
                        $q->where('reason', 'call back later')
                          ->orWhere('reason', 'Not pickup');
                    });
                    break;
                case 'today_cold_clients':
                    $query->whereHas('Followup', function($q) {
                        $q->where('reason', 'call back later')
                          ->orWhere('reason', 'Not pickup')
                          ->whereDate('next_date', Carbon::today());
                    });
                    break;
                case 'rejects':
                    $query->whereHas('Followup', function($q) {
                        $q->where('reason','Wrong Information');
                    });
                    break;
                case 'today_reject':
                    $query->whereHas('Followup', function($q) {
                        $q->where('reason','Wrong Information')
                        ->whereDate('created_at', Carbon::today());
                    });
                    break;
                default:
                $query->whereHas('Followup', function($q) {
                    $q->whereNotIn('reason', ['Wrong Information', 'Not interested', 'Work with other company']);
                });
                break;
            }
        }
    
        // Get paginated results
        $leads = $query->paginate(50);
         
        //card filter based on dates
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if($startDate && $endDate){
            $today_leads =  Lead::whereBetween('created_at', [$startDate, $endDate])->count();
            $today_followup = Followup::whereNotNull('lead_id')->whereBetween('created_at', [$startDate, $endDate])->select('lead_id')->distinct()->count();
            $today_proposal =  Lead::whereBetween('created_at', [$startDate, $endDate])->where('mail_status',1)->count();
        }else{
            $total_leads =  Lead::count();
            $total_followup = Followup::whereNotNull('lead_id')->select('lead_id')->distinct()->count();
            $today_leads =  Lead::where('created_at',Carbon::today())->count();
            $today_followup = Followup::whereNotNull('lead_id')
                                    ->whereDate('created_at', Carbon::today())
                                    ->distinct('lead_id')
                                    ->count('lead_id');
            $next_today_followup = Followup::whereNotNull('lead_id')->whereDate('next_date',Carbon::today())->select('lead_id')->distinct()->count();
            $today_proposal =   Lead::whereDate('mail_date', Carbon::today())->where('mail_status',1)->count();
            $total_delay =  Followup::where('delay', 1)->orWhere('is_completed', '!=', 1)->count();
            $total_reject = Followup::where('reason','Wrong Information')->count();
            $total_cold_clients = Followup::where('reason','Not interested')->orWhere('reason', 'Not pickup')->count();
            $today_total_cold_clients = Followup::whereIn('reason', ['Not interested', 'Work with other company'])->whereDate('next_date',Carbon::today())->count();
            $today_total_reject = Followup::where('reason', 'Wrong Information')->whereDate('next_date',Carbon::today())->count();
   
        
        // If AJAX request, return partial views for leads and pagination
        if ($request->ajax()) {
            return response()->json([
                'leads' => view('admin.crm.test.index-table', compact('users', 'leads', 'countries', 'services', 'countries', 'projectCategories', 'categories', 'messagetemplates',))->render(),
                'pagination' => view('admin.crm.test.pagination', compact('leads'))->render(),
                'today_leads' => $today_leads,
                'today_followup' => $today_followup,
                'today_proposal' => $today_proposal,
                'bdeReports' => $bdeReports,
                'userRoleData' => $userRoleData,
            ]);
        }

        // Return the main view if not AJAX
        return view('admin.crm.test.index', compact('userRoleData','users', 'leads', 'countries', 'services', 'projectCategories', 'categories', 'messagetemplates','today_leads','today_followup','today_proposal','total_leads','total_followup','bdeReports','next_today_followup'
        ,'total_delay','total_reject','today_total_reject','total_cold_clients'));
    }
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

        if($request->has('country') && isset($request->country)){
            // dd($request->country);
            $query->where('country', $request->input('country'));
        }
    
        if ($request->has('lead_day')) {
            $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
        }
    
        if ($request->has('lead_status') && isset($request->lead_status) ) {
            $status = $request->input('lead_status');
            if($status == 7){
                $query->where('status', $status !== null ? 1 : 0);
            }else{
                $query->where('lead_status', $status !== null ? $status : 0);
            }
        }
    
        if ($request->has('category') && isset($request->category)) {
            $query->where('client_category', $request->input('category'));
        }
    
        if ($request->has('service') && isset($request->service)) {
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

        if($request->has('search_bde')){
            $query->where('assigned_by', $request->input('search_bde'));
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
            case 'delay':
                $query->whereHas('Followup', function($q) {
                    $q->where('delay', 1)
                      ->orWhere('is_completed', '!=', 1);
                });
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
            'delay' => 0,
            'freshLead' => 0,
            'today_freshLead' => 0,
            'total_reject' => 0,
            'today_total_reject' => 0,
            'cold_clients' => 0,
            'today_cold_clients' => 0,
            'followupCompleted' =>0,
            'followupPending' =>0,
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
                                             ->orWhere('assigned_by', $userId);
                                       })->count();
            $data['month_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfMonth())
                                       ->where(function($q) use ($userId) {
                                           $q->where('user_id', $userId)
                                             ->orWhere('assigned_by', $userId);
                                       })->count();
            $data['year_leads'] = Lead::where('created_at', '>=', Carbon::now()->startOfYear())
                                      ->where(function($q) use ($userId) {
                                          $q->where('user_id', $userId)
                                            ->orWhere('assigned_by', $userId);
                                      })->count();
            $data['convert_leads'] = Lead::where('status', 1)
                                         ->where(function($q) use ($userId) {
                                             $q->where('user_id', $userId)
                                               ->orWhere('assigned_by', $userId);
                                         })->count();
            $data['total_leads'] = Lead::where(function($q) use ($userId) {
                                             $q->where('user_id', $userId)
                                               ->orWhere('assigned_by', $userId);
                                         })->count();
            $data['leads'] = $query->with('countries')->where(function($q) use ($userId) {
                                      $q->where('user_id', $userId)
                                        ->orWhere('assigned_by', $userId);
                                  })->orderBy('id', 'desc')->paginate(20);
                                  
            $data['today_fresh'] = Lead::where('status', '!=', 1)
                                       ->where('created_at', '>=', Carbon::today())
                                       ->where(function($q) use ($userId) {
                                           $q->where('user_id', $userId)
                                             ->orWhere('assigned_by', $userId);
                                       })->count();
            $data['fresh_lead'] = Lead::where('status', '!=', 1)
                                      ->where(function($q) use ($userId) {
                                          $q->where('user_id', $userId)
                                            ->orWhere('assigned_by', $userId);
                                      })->count();
            $data['today_proposal'] = DB::table('prposal')->whereNotNull('lead_id')
                                                           ->where('created_at', '>=', Carbon::today())
                                                           ->where('user_id', $userId)->count();
            $data['total_proposal'] = DB::table('prposal')->whereNotNull('lead_id')
                                                           ->where('user_id', $userId)->count();
            $data['today_followup'] = Followup::whereNotNull('lead_id')
                                              ->where('created_at', '>=', Carbon::today())
                                              ->where('user_id', $userId)
                                              ->select('lead_id')->distinct()->count();
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
                                               ->orWhere('assigned_by', $userId);
                                         })->count();
            
            $data['delay'] = Followup::where('delay', 1)->orWhere('is_completed', '!=', 1)->where('user_id',auth()->user()->id)->count();
            // $data['total_revenue'] = Payment::where('lead_id', $query->id)
            //                                 ->whereDate('created_at', Carbon::today())
            //                                 ->sum('amount');
                                             //fresh lead
            $data['freshLead']= Lead::where('assigned_by', auth()->user()->id)
                                    ->whereDoesntHave('Followup', function ($q) {
                                        $q->whereNotNull('lead_id');
                                    })
                                    ->count();
            // today fresh lead
            $data['today_freshLead'] = Lead::whereDate('created_at', Carbon::today())->where('assigned_by',auth()->user()->id)
                                    ->whereDoesntHave('Followup', function ($q) {
                                    $q->whereNotNull('lead_id');
                                    })
                                    ->count();

                                    FollowUp::where('reason', 'Wrong Information')->whereDate('created_at', today())
                                    ->whereHas('lead', function ($query) {
                                        $query->where('assigned_by',auth()->user()->id);
                                    })->distinct('lead_id')
                                    ->count();

            $data['today_total_reject'] = FollowUp::where('reason', 'Wrong Information')->whereDate('created_at', today())
                                    ->whereHas('lead', function ($query) {
                                        $query->where('assigned_by',auth()->user()->id);
                                    })->distinct('lead_id')
                                    ->count();          
            $data['total_reject'] = FollowUp::where('reason', 'Wrong Information')
                                ->whereHas('lead', function ($query) {
                                    $query->where('assigned_by',auth()->user()->id);
                                })->distinct('lead_id')
                                ->count(); 

            $data['cold_clients'] = Followup::where('reason','Not interested')->orWhere('reason', 'Not pickup') 
                                            ->whereHas('lead', function ($query) {
                                                $query->where('assigned_by',auth()->user()->id);
                                            })->distinct('lead_id')->count();       
            $data['today_cold_clients'] = Followup::where('reason','Not interested')->whereDate('created_at',Carbon::today())->orWhere('reason', 'Not pickup')
                                            ->whereHas('lead', function ($query) {
                                                $query->where('assigned_by',auth()->user()->id);
                                            })->distinct('lead_id')->count();  
            $data['followupPending'] = Followup::where('is_completed','=!',1)
                                            ->whereHas('lead', function ($query) {
                                                $query->where('assigned_by',auth()->user()->id);
                                            })->distinct('lead_id')->count(); 
            $data['followupCompleted'] = Followup::where('is_completed',1)
                                        ->whereHas('lead', function ($query) {
                                            $query->where('assigned_by',auth()->user()->id);
                                        })->distinct('lead_id')->count(); 
        }else {
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
            $data['delay'] = Followup::where('delay', 1)->orWhere('is_completed', '!=', 1)->count();
            //fresh lead
            $data['freshLead']= Lead::whereDoesntHave('Followup', function ($q) {
                                    $q->whereNotNull('lead_id');
                                })->count();
            // today fresh lead
            $data['today_freshLead'] = Lead::whereDate('created_at', Carbon::today())
                                            ->whereDoesntHave('Followup', function ($q) {
                                                $q->whereNotNull('lead_id');
                                            })->count();

            $data['today_total_reject'] = Followup::where('reason', 'Wrong Information')->whereDate('created_at',Carbon::today())->distinct('lead_id')->count();            
            $data['total_reject'] = Followup::where('reason','Wrong Information')->distinct('lead_id')->count();
            $data['cold_clients'] = Followup::where('reason','Not interested')->orWhere('reason', 'Not pickup')->distinct('lead_id')->count();
            $data['today_cold_clients'] = Followup::where('reason','Not interested')->orWhere('reason', 'Not pickup')->whereDate('created_at',Carbon::today())->distinct('lead_id')->count();
            $data['followupPending'] = Followup::where('is_completed','=!',1)->distinct('lead_id')->count();
            $data['followupCompleted'] = Followup::where('is_completed',1)->distinct('lead_id')->count();
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
                    'id' => $bde->id,
                    'assigned_leads' => Lead::whereDate('created_at', Carbon::today())
                        ->where(function($q) use ($bdeId) {
                            $q->where('user_id', $bdeId)
                            ->orWhere('assigned_user_id', $bdeId);
                        })->count(),
                    'followups' => Followup::whereNotNull('lead_id')
                        ->whereDate('created_at', Carbon::today())
                        ->where('user_id', $bdeId)
                        ->distinct('lead_id')
                        ->count(),
                    'calls' => Followup::whereNotNull('lead_id')
                                ->whereDate('created_at', Carbon::today())
                                ->where('user_id', $bdeId)
                                ->distinct('lead_id')
                                ->count(), // Adjust this as needed
                    'proposals' =>  Lead::whereDate('mail_date', Carbon::today())->where('mail_status',1)
                                    ->where(function($q) use ($bdeId) {
                                        $q->where('user_id', $bdeId)
                                        ->orWhere('assigned_user_id', $bdeId);
                                    })->count(),

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