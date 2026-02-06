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
    private function initializeLeadQuery()
    {
        return Lead::with('category', 'totalAmount', 'Followup', 'countries', 'lastFollowup');
    }
    public function index(Request $request)
    {
        $user = auth()->user();
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

        # get counts
        $userRoleData = $this->handleRoleBasedLogic($user, $query);

        // counts
        $count = [];
        if ($user && $user->hasRole(['BDE', 'Business Development Intern'])) {
            $count['leads'] = Lead::where(function ($query) {
                $query->whereDate('created_at', Carbon::today())->orWhereDate('assigned_date', Carbon::today());
            })
                ->where('assigned_user_id', $user->id)
                ->count();
            $count['followups'] = Followup::whereHas('lead')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereDate('created_at', Carbon::today());
                })
                ->distinct('lead_id')
                ->count();
            $count['proposals'] = Lead::whereDate('proposal_date', Carbon::today())->where('proposal', 1)->where('assigned_user_id', $user->id)->count();
            $count['quotation'] = Lead::whereDate('quotation_date', Carbon::today())->where('quotation', 1)->where('assigned_user_id', $user->id)->count();
            $count['delay'] = 0;
            $count['reject'] = FollowUp::whereHas('lead')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('reason', ['Wrong Information', 'Not interested', 'Work with other company'])->whereDate('created_at', Carbon::today());
                })
                ->distinct('lead_id')
                ->count();
            $count['revenue'] = 0;
        } else {
            $count['leads'] = Lead::whereDate('created_at', Carbon::today())->count();
            $count['followups'] = Followup::whereHas('lead')->whereDate('created_at', Carbon::today())->select('lead_id')->distinct()->count();
            $count['proposals'] = Lead::whereDate('proposal_date', Carbon::today())->where('proposal', 1)->count();
            $count['quotation'] = Lead::whereDate('quotation_date', Carbon::today())->where('quotation', 1)->count();
            $count['revenue'] = 0;
            $count['delay'] = Followup::whereHas('lead')
                ->where(function ($query) {
                    $query->where('delay', 1)->orWhere('is_completed', '!=', 1);
                })
                ->where('next_date', Carbon::today())
                ->distinct('lead_id')
                ->count();
            $count['reject'] = Followup::whereHas('lead')
                ->whereIn('reason', ['Wrong Information', 'Not interested', 'Work with other company'])
                ->whereDate('created_at', Carbon::today())
                ->select('lead_id')
                ->distinct()
                ->count();
        }

        return view('admin.crm.index', compact('userRoleData', 'users', 'countries', 'services', 'projectCategories', 'categories', 'messagetemplates', 'bdeReports', 'count'));
    }

    public function data(Request $request)
    {
        // 1. Pre-fetch Static Data (Optimizes the 'service' column N+1 issue)
        // Instead of querying DB for every row, we load names into memory once.
        $projectCategories = ProjectCategory::pluck('name', 'id')->toArray();

        // 2. Query Construction
        $user = auth()->user();

        $query = Lead::with([
            'category',
            'countries',
            'user',
            'AssignedUser',
            'invoice',
            // Load only the latest followup to save memory
            'Followup' => fn($q) => $q->latest()->limit(1),
        ])
            // Let SQL do the counting instead of PHP
            ->withCount(['Followup as total_followups', 'Followup as delayed_followups' => fn($q) => $q->where('delay', 1)]);

        // 3. Apply Role Scoping
        $query->when($user && $user->hasRole(['BDE', 'Business Development Intern']), function ($q) use ($user) {
            $q->where('assigned_user_id', $user->id);
        });

        // 4. Apply Filters & Ordering
        $this->applyFilters($query, $request);
        $query->orderByDesc('id');

        // 5. Build DataTable
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('client_info', function ($lead) {
                // Helper variables to keep logic separate from HTML
                $lead = EncodingHelper::sanitizeUtf8($lead); // Keep your custom helper

                // Logic
                $daysDiff = now()->diffInDays($lead->created_at);
                $maskedMobile = substr($lead->phone, 0, 5) . '******';
                $categoryName = $lead->category->name ?? 'N/A';

                // Status Badge Logic
                $statusBadge = match (true) {
                    $lead->status == 1 => '<span class="badge bg-success">Convert</span>',
                    $lead->lead_status == 1 => '<span class="badge bg-danger">Hot</span>',
                    $lead->lead_status == 2 => '<span class="badge bg-warning">Warm</span>',
                    default => '<span class="badge bg-primary">Cold</span>',
                };

                // Source Logic
                $leadSource = match ($lead->lead_source) {
                    1 => 'Website',
                    2 => 'Social Media',
                    3 => 'Reference',
                    default => 'Bulk Lead',
                };

                // Edit Button Logic (Using json_encode for safe JS parameters)
                $editBtn = '';
                if ($lead->status != 1) {
                    $jsParams = json_encode([$lead->id, $lead->name, $lead->email, $lead->country, $lead->phone, $lead->city, $lead->client_category, $lead->website, $lead->domian_expire, $lead->lead_status, $lead->lead_source, $lead->ref_name, $lead->assigned_user_id]);
                    // Strip the outer brackets of json_encode to fit your function signature
                    $jsParams = trim($jsParams, '[]');
                    $editBtn = "<span class='badge text-dark' style='cursor:pointer' onclick='EditLead($jsParams)'>Edit</span>";
                }

                // Followup Delay Logic
                $delayBadge = '';
                $lastFollowup = $lead->Followup->first(); // Since we limited eager load to 1
                if ($lastFollowup && $lastFollowup->next_date < now()) {
                    $daysLate = now()->diffInDays($lastFollowup->next_date);
                    $delayBadge = "<span class='badge bg-danger'>Last followup expired: $daysLate days ago</span>";
                }

                $emailLink = !empty($lead->email) ? "<small><a href='mailto:{$lead->email}'><i class='bi bi-envelope'></i> {$lead->email}</a></small><br>" : '';
                $daysAgoBadge = $daysDiff > 1 ? "<span class='badge bg-secondary'>{$daysDiff} Days Ago</span>" : '';
                $createdAt = $lead->created_at->format('d-m-y H:i:s');
                $shortName = substr(ucfirst($lead->name), 0, 20) . '..';

                // Heredoc for clean HTML
                return <<<HTML
                                <div class='order-md-1'>
                                    <h6 class='mb-1 text-dark fs-15 lead-name fw-bold' data-id='{$lead->id}' data-name='{$lead->name}' style='cursor:pointer'>{$shortName}</h6>
                                    <small class='text-muted'>({$categoryName})</small> |
                                    <small class='text-muted'>{$editBtn}</small> |
                                    <small class='badge text-muted bg-muted'>{$leadSource}</small> |
                                    {$statusBadge} <br>
                                    <small onclick="Followup({$lead->id}, '{$lead->name}', '{$lead->phone}', 0)">
                                        <a href='#'><i class='bi bi-telephone'></i> {$maskedMobile}</a>
                                    </small><br>
                                    {$emailLink}
                                    <small><i class='bi bi-bag-plus'></i> Create Date: {$createdAt}</small>
                                    {$daysAgoBadge}<br>
                                    {$delayBadge}
                                </div>
                HTML;
            })
            ->addColumn('service', function ($lead) use ($projectCategories) {
                if (empty($lead->project_category)) {
                    return 'No Service';
                }

                // FIX: Check if it's already an array (due to Model Casting)
                $ids = $lead->project_category;

                if (is_string($ids)) {
                    $ids = json_decode($ids, true);
                }

                // Safety check: ensure we have an array after decoding
                if (!is_array($ids)) {
                    return 'Invalid Data';
                }

                // Map IDs to names using the pre-fetched array
                $names = array_map(fn($id) => $projectCategories[$id] ?? '', $ids);

                return implode('<br>', array_filter($names));
            })
            ->addColumn('location', function ($lead) {
                $country = e($lead->countries->nicename ?? 'N/A');
                $city = e($lead->city ?? 'N/A');

                return <<<HTML
                                <strong class='lead-country' data-id='{$lead->id}' data-country='{$country}' style='cursor:pointer'>
                                    <i class='bi bi-geo-alt-fill'></i> {$country}
                                </strong><br>
                                <small class='lead-city' data-id='{$lead->id}' data-city='{$city}' style='cursor:pointer'>({$city})</small>
                HTML;
            })
            ->addColumn('followup', function ($lead) {
                // 1. Sanitize the object
                $lead = EncodingHelper::sanitizeUtf8($lead);

                // 2. Cache collection
                $followups = $lead->followup;
                $totalCount = $followups->count();

                // 3. Prepare JS Variables
                $leadName = addslashes(e($lead->name));
                $leadPhone = addslashes(e($lead->phone));

                // 4. Build the Button
                $countLabel = $totalCount >= 1 ? " ({$totalCount})" : '';

                $html = "<a class='btn btn-primary btn-sm followupBtn' 
               data-lead-id='{$lead->id}' 
               onclick=\"Followup({$lead->id}, '{$leadName}', '{$leadPhone}', 1)\">
               Follow up{$countLabel}
             </a><br>";

                // 5. Handle Last Follow-up details
                if ($totalCount >= 1) {
                    $last = $followups->last(); // Get the single last object

                    $formattedDate = $last->created_at->format('d-m-y H:i:s');
                    $reason = e($last->reason);

                    $html .= "<small class='text-muted'>(Last Follow-up: {$formattedDate})</small><br>";
                    $html .= "<small>Reason: {$reason}</small><br>";

                    // 6. Handle Delay (Last Follow-up ONLY)
                    // Check if the specific last item has a delay greater than 0
                    if ($last->delay > 0) {
                        $html .= "<span class='badge bg-danger' style='font-size: 0.75rem;'>Delay: {$last->delay} Days</span><br>";
                    }
                }

                return $html;
            })
            ->addColumn('quotation', function ($lead) {
                $buttons = <<<HTML
                                <a class="btn btn-sm btn-outline-warning" onclick="SendMessage({$lead->id})" data-bs-toggle="tooltip" title="Send Portfolio">
                                    <i class="bi bi-chat-dots"></i> Send Portfolio
                                </a><br>
                                <a class="btn btn-sm btn-outline-primary mt-2" onclick="SendProposal({$lead->id})" data-bs-toggle="tooltip" title="Send Proposal">
                                    <i class="bi bi-file-text"></i> Send Proposal
                                </a><br>
                HTML;

                if ($lead->quotation == 1) {
                    $url = route('crm.prposel.mail.view', ['leadId' => $lead->id]);
                    $date = Carbon::parse($lead->quotation_date)->format('d-m-y H:i:s');
                    $buttons .= <<<HTML
                                        <a href="{$url}" class="mt-2 btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Resend Quotation">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Resend Quotation
                                        </a><br>
                                        <small>(Send Date: {$date})</small>
                    HTML;
                } else {
                    $url = route('crm.quotation.client', ['id' => $lead->id]);
                    $buttons .= <<<HTML
                                        <a href="{$url}" class="mt-2 btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Send Quotation">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Send Quotation
                                        </a>
                    HTML;
                }
                return $buttons;
            })
            ->addColumn('assigned_info', function ($lead) {
                $creator = e($lead->user->name ?? 'N/A');
                $assigner = e($lead->user->name ?? 'N/A'); // Logic check: Is assigner same as creator?
                $assignee = e($lead->AssignedUser->name ?? 'N/A'); // Fixed relationship access from assignd_user to AssignedUser

                return <<<HTML
                                <small>Created by : <strong>{$creator}</strong></small><br>
                                <small>Assigned by: <strong>{$assigner}</strong></small><br>
                                <small>Assigned User: <strong>{$assignee}</strong></small>
                HTML;
            })
            ->addColumn('actions', function ($lead) {
                if ($lead->quotation != 1) {
                    return '-';
                }

                $viewUrl = route('crm.prposel.mail.view', ['leadId' => $lead->id]);
                $paidOption = '';

                if ($lead->invoice) {
                    $paidOption = "<li><a class='dropdown-item' onclick=\"MarkAsPaid({$lead->invoice->id}, {$lead->invoice->balance}, '{$lead->name}')\">Mark as Paid</a></li>";
                }

                return <<<HTML
                                <div class='dropdown'>
                                    <button class='btn btn-outline-default' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                                    <ul class='dropdown-menu'>
                                        {$paidOption}
                                        <li><a class='dropdown-item' href='{$viewUrl}'>View Quotation</a></li>
                                    </ul>
                                </div>
                HTML;
            })
            ->rawColumns(['checkbox', 'client_info', 'service', 'location', 'followup', 'quotation', 'assigned_info', 'actions'])
            ->make(true);
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search') && ($search = $request->input('search')['value'])) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('country') && isset($request->country)) {
            $query->where('country', $request->input('country'));
        }

        if ($request->has('lead_day')) {
            $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
        }

        if ($request->has('status') && isset($request->status)) {
            $status = $request->input('status');
            if ($status == 7) {
                $query->where('status', $status !== null ? 1 : 0);
            } else {
                $query->where('lead_status', $status !== null ? $status : 0);
            }
        }

        if ($request->has('category') && isset($request->category)) {
            $query->where('client_category', $request->input('category'));
        }

        if ($request->has('service') && isset($request->service)) {
            $query->whereJsonContains('project_category', (string) $request->service);
        }

        if ($request->has('proposal')) {
            $this->applyProposalFilter($query, $request->input('proposal'), $request);
        }

        if ($request->has('quotation')) {
            $this->applyQuatitonFilter($query, $request->input('quotation'), $request);
        }

        if ($request->has('bde') && isset($request->bde)) {
            $query->where('assigned_user_id', $request->input('bde'));
        }

        if ($request->has('followup')) {
            $this->applyFollowupFilter($query, $request->input('followup'), $request);
        }

        if ($request->has('lead_type') && isset($request->lead_type)) {
            $this->applyButtonFilter($query, $request->input('lead_type'), $request);
        }

        if ($request->has('lead_sub_type') && isset($request->lead_sub_type)) {
            $this->applyButtonFilter($query, $request->input('lead_sub_type'), $request);
        }

        if ($request->has('start_date') && isset($request->start_date) && $request->has('end_date') && isset($request->end_date)) {
            $start = $request->start_date . ' 00:00:00';
            $end = $request->end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
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
                $query->whereDate('proposal_date', Carbon::today())->where('proposal', 1);
                break;
            case 'month':
                $query->whereMonth('proposal_date', Carbon::now()->month)->where('proposal', 1);
                break;
            case 'year':
                $query->whereYear('proposal_date', Carbon::now()->year)->where('proposal', 1);
                break;
            case 'custome':
                if ($request->has('from_date') && $request->has('to_date')) {
                    $fromDate = Carbon::parse($request->input('from_date'));
                    $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate])->where('proposal', 1);
                }
                break;
        }
    }

    private function applyQuatitonFilter($query, $quotation, Request $request)
    {
        switch ($quotation) {
            case 'today':
                $query->whereDate('quotation_date', Carbon::today())->where('quotation', 1);
                break;
            case 'month':
                $query->whereMonth('quotation_date', Carbon::now()->month)->where('quotation', 1);
                break;
            case 'year':
                $query->whereYear('quotation_date', Carbon::now()->year)->where('quotation', 1);
                break;
            case 'custome':
                if ($request->has('from_date') && $request->has('to_date')) {
                    $fromDate = Carbon::parse($request->input('from_date'));
                    $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate])->where('quotation', 1);
                }
                break;
        }
    }

    private function applyFollowupFilter($query, $followup, Request $request)
    {
        switch ($followup) {
            case 'today':
                $query->whereHas('Followup', function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                });
                break;
            case 'this_week':
                $query->whereHas('Followup', function ($q) {
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                });
                break;
            case 'month':
                $query->whereHas('Followup', function ($q) {
                    $q->whereMonth('created_at', now()->month);
                });
                break;
            case 'today_followup':
                $query->whereHas('Followup', function ($q) {
                    $q->whereDate('next_date', now()->toDateString());
                });
                break;
            case 'today_converted':
                $query->whereDate('created_at', Carbon::today())->where('status', 1);
                break;
        }
        return $query;
    }

    private function applyButtonFilter($query, $type, Request $request)
    {
        if (!$type) {
            return;
        }

        $user = auth()->user();
        $userId = $user?->id;


        $today = Carbon::today();
        $now = Carbon::now();

        $isAdmin = $user && $user->role_id == 1;
        $isBDE   = $user && $user->hasRole(['BDE', 'Business Development Intern']);

        $scopeUser = function ($q) use ($isBDE, $userId) {
            if ($isBDE) {
                $q->where(function ($sub) use ($userId) {
                    $sub->where('user_id', $userId)
                        ->orWhere('assigned_by', $userId)
                        ->orWhere('assigned_user_id', $userId);
                });
            }
            // ✅ Admin → no condition → sees all
        };

        $scopeExcludeRejects = function ($q) {
            $q->whereDoesntHave('lastFollowup', function ($sq) {
                $sq->whereIn('reason', ['Not interested', 'Wrong Information', 'Work with other company']);
            });
        };

        $scopeIncomplete = function ($q) {
            $q->where(function ($sub) {
                $sub->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
            });
        };

        $query->tap($scopeUser);

        switch ($type) {
            case 'all_lead':
                break;

            case 'fresh_lead':
                $query->whereDoesntHave('Followup');
                break;

            case 'today_fresh':
            case 'today_fresh_lead':
                $query->whereDate('created_at', $today)->whereDoesntHave('Followup');
                break;

            case 'hot_client':
                $query->tap($scopeExcludeRejects)->where('lead_status', 1);
                break;

            case 'today_hot_client':
                $query->tap($scopeExcludeRejects)->where('lead_status', 1)->whereHas('Followup', fn($q) => $q->whereDate('next_date', $today));
                break;

            case 'today_pending_followup':
                $query->tap($scopeExcludeRejects)->whereHas('Followup', function ($q) use ($today, $isBDE, $userId, $scopeIncomplete) {
                    $q->whereDate('next_date', $today)->tap($scopeIncomplete);

                    if ($isBDE) {
                        $q->where('user_id', $userId);
                    }
                });
                break;

            case 'all_followup':
                $query->whereHas('Followup', fn($q) => $q->whereNotIn('reason', ['Work with other company', 'Wrong Information', 'Not interested']));
                break;

            case 'followup_pending':
                $query->whereHas('Followup', fn($q) => $q->tap($scopeIncomplete));
                break;

            case 'followup_completed':
                $query->whereHas('Followup', fn($q) => $q->where('is_completed', 1));
                break;

            case 'followup_other':
                $query->whereHas('Followup', fn($q) => $q->where('reason', 'Other'));
                break;

            case 'followup_interested':
                $query->whereHas('lastFollowup', fn($q) => $q->where('reason', 'Interested'));
                break;

            case 'cold_clients':
                $query->tap($scopeExcludeRejects)->whereHas('Followup', fn($q) => $q->whereIn('reason', ['call back later', 'Not pickup']));
                break;

            case 'today_cold_clients':
                $query->tap($scopeExcludeRejects)->whereHas('Followup', fn($q) => $q->whereDate('created_at', $today)->whereIn('reason', ['call back later', 'Not pickup']));
                break;

            case 'convert_leads':
                $query->where('status', 1);
                break;

            case 'today_converted':
                $query->where('status', 1)->whereDate('created_at', $today);
                break;

            case 'today_created_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', $today));
                break;

            case 'today_followup':
                $query->whereHas('Followup', fn($q) => $q->whereDate('next_date', $today));
                break;

            case 'yesterday_followup':
                $yesterday = Carbon::yesterday();
                $query->whereHas('Followup', fn($q) => $q->whereDate('created_at', $yesterday)->orWhereDate('next_date', $yesterday));
                break;

            case 'last_7_days_followup':
            case 'this_month_followup':
                $start = $type === 'last_7_days_followup' ? $now->copy()->subDays(7) : $now->copy()->startOfMonth();

                $query->whereHas('Followup', fn($q) => $q->whereBetween('created_at', [$start, $now])->orWhereBetween('next_date', [$start, $now]));
                break;

            case 'total_reject':
            case 'rejects':
                $query->whereHas('lastFollowup', fn($q) => $q->whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested']));
                break;

            case 'today_total_reject':
            case 'today_reject':
                $query->whereHas('lastFollowup', fn($q) => $q->whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested'])->whereDate('created_at', $today));
                break;

            case 'delay':
                $query
                    ->whereHas('Followup', function ($q) use ($scopeIncomplete) {
                        $q->where('delay', '>=', 1)
                            ->tap($scopeIncomplete)
                            ->whereNull('deleted_at');
                    })

                    // latest followup must NOT be rejected
                    ->whereDoesntHave('lastFollowup', function ($q) {
                        $q->whereIn('reason', [
                            'Not interested',
                            'Wrong Information',
                            'Work with other company'
                        ])->whereNull('deleted_at');
                    });

                break;

            case 'today_delay':
                $query
                    ->whereHas('Followup', function ($q) use ($today, $scopeIncomplete) {
                        $q->whereDate('next_date', $today)
                            ->where('delay', '>=', 1)
                            ->tap($scopeIncomplete)
                            ->whereNull('deleted_at');
                    })

                    // latest followup must NOT be rejected
                    ->whereDoesntHave('lastFollowup', function ($q) {
                        $q->whereIn('reason', [
                            'Not interested',
                            'Wrong Information',
                            'Work with other company'
                        ])->whereNull('deleted_at');
                    });

                break;

            case 'delay_1_days':
case 'delay_2_days':
case 'delay_3_days':
case 'delay_4_days':
case 'delay_5_plus':

    $days = (int) filter_var($type, FILTER_SANITIZE_NUMBER_INT);

    $query
        ->whereHas('Followup', function ($q) use ($days, $type, $scopeIncomplete) {

            // ❌ Exclude today
            $q->whereDate('next_date', '<', Carbon::today());

            if ($type === 'delay_5_plus') {
                $q->where('delay', '>=', 5);
            } else {
                $q->where('delay', $days);
            }

            $q->tap($scopeIncomplete)
              ->whereNull('deleted_at');
        })

        // ✅ Correct whereIn usage
        ->whereDoesntHave('lastFollowup', function ($q) {
            $q->whereIn('reason', [
                'Not interested',
                'Wrong Information',
                'Work with other company'
            ])->whereNull('deleted_at');
        });

    break;

            default:
                $query
                    ->whereHas('Followup', function ($q) use ($scopeIncomplete) {
                        $q->where('delay')->tap($scopeIncomplete)->whereNull('deleted_at');
                    })

                    // 🔴 THIS IS THE IMPORTANT PART (NOT EXISTS latest rejected followup)
                    ->whereDoesntHave('lastFollowup', function ($q) {
                        $q->whereIn('reason', ['Not interested', 'Wrong Information', 'Work with other company'])->whereNull('deleted_at');
                    });
                break;
        }
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
            'followup_completed_today' => 0,
            'total_amount' => 0,
            'total_revanue' => 0,
            'today_converted' => 0,
            'converted_completed_today' => 0,
            'delay' => 0,
            'freshLead' => 0,
            'today_freshLead' => 0,
            'total_reject' => 0,
            'today_total_reject' => 0,
            'cold_clients' => 0,
            'today_cold_clients' => 0,
            'followupCompleted' => 0,
            'followupPending' => 0,
        ];
    }

    private function handleRoleBasedLogic($user, $query)
    {
        $data = [];
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // 1. Setup User Logic
        $isBDE = $user && $user->hasRole(['BDE', 'Business Development Intern']);
        $userId = $user ? $user->id : null;

        // --- HELPER 1: Apply "My Data" Scope ---
        $applyUserScope = function ($q) use ($userId) {
            return $q->where(function ($sub) use ($userId) {
                $sub->where('user_id', $userId)->orWhere('assigned_by', $userId)->orWhere('assigned_user_id', $userId);
            });
        };

        // --- HELPER 2: Exclude "Not Interested" ---
        // We will ONLY apply this to Hot, Cold, Delay, and Pending lists.
        $excludeNotInterested = function ($q) {
            $q->whereDoesntHave('lastFollowup', function ($sq) {
                $sq->where('reason', 'Not interested');
            });
        };

        if ($isBDE) {
            // ==========================================
            //            BDE / INTERN LOGIC
            // ==========================================

            // 1. Base Query for ACTIVE LISTS (Hot, Cold, Delay, Pending)
            // We APPLY the exclusion here
            $activeWorkQuery = Lead::query()->tap($applyUserScope)->tap($excludeNotInterested);

            // 2. Base Query for RAW STATS (Fresh, History)
            // We DO NOT apply the exclusion here (as requested)
            $rawUserQuery = Lead::query()->tap($applyUserScope);

            // --- LEAD COUNTS (ACTIVE WORK) ---
            // "Hot" means active, so we hide "Not Interested"
            $data['hot_client'] = (clone $activeWorkQuery)->where('lead_status', 1)->count();

            $data['today_hot_client'] = (clone $activeWorkQuery)
                ->where('lead_status', 1)
                ->whereHas('Followup', function ($q) use ($today) {
                    $q->whereDate('next_date', $today);
                })
                ->count();

            // --- LEAD COUNTS (RAW TOTALS) ---
            // No exclusion applied here
            $data['total_leads'] = (clone $rawUserQuery)->count();
            $data['today_leads'] = (clone $rawUserQuery)->where('created_at', '>=', $today)->count();
            $data['month_leads'] = (clone $rawUserQuery)->where('created_at', '>=', $startOfMonth)->count();
            $data['year_leads'] = (clone $rawUserQuery)->where('created_at', '>=', $startOfYear)->count();
            $data['convert_leads'] = Lead::where('status', 1)->count();

            // --- FRESH LEADS (Requested: NO FILTER) ---
            // Using $rawUserQuery so "Not Interested" are still counted here if they exist
            $data['today_fresh'] = (clone $rawUserQuery)->where('status', '!=', 1)->where('created_at', '>=', $today)->count();
            $data['fresh_lead'] = (clone $rawUserQuery)->where('status', '!=', 1)->count();

            $data['freshLead'] = (clone $rawUserQuery)->whereDoesntHave('Followup')->count();

            $data['today_freshLead'] = (clone $rawUserQuery)->whereDate('created_at', $today)->whereDoesntHave('Followup')->count();

            // --- PAGINATED LIST ---
            // Usually you want the list clean, so we apply Exclusion.
            $data['leads'] = $query
                ->with('countries')
                ->tap($applyUserScope)
                ->tap($excludeNotInterested) // Remove this line if you want to see Rejects in the main table too
                ->orderBy('id', 'desc')
                ->paginate(20);

            // --- FOLLOWUPS (HISTORY / LOGS) ---
            // Requested: NO FILTER on these counts
            $baseFollowup = Followup::where('user_id', $userId)->whereHas('lead');

            $data['today_created_followup'] = (clone $baseFollowup)->whereDate('created_at', $today)->distinct('lead_id')->count();
            $data['today_followup'] = (clone $baseFollowup)->whereDate('next_date', $today)->distinct('lead_id')->count();

            $data['yesterday_followup'] = (clone $baseFollowup)
                ->where(function ($q) use ($yesterday) {
                    $q->whereDate('created_at', $yesterday)->orWhereDate('next_date', $yesterday);
                })
                ->distinct('lead_id')
                ->count();

            $data['last7Days_followup'] = (clone $baseFollowup)
                ->where(function ($q) {
                    $q->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->orWhereBetween('next_date', [Carbon::now()->subDays(7), Carbon::now()]);
                })
                ->distinct('lead_id')
                ->count();

            $data['thisMonth_followup'] = (clone $baseFollowup)
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereBetween('created_at', [$startOfMonth, Carbon::now()])->orWhereBetween('next_date', [$startOfMonth, Carbon::now()]);
                })
                ->distinct('lead_id')
                ->count();

            $data['total_followup'] = (clone $baseFollowup)
                ->whereNotIn('reason', ['Work with other company', 'Wrong Information', 'Not interested'])
                ->distinct('lead_id')
                ->count();

            $data['followup_today'] = Followup::whereNotNull('lead_id')->where('user_id', $userId)->where('next_date', $today)->count();

            // --- FOLLOWUPS (WORK QUEUE) ---
            // These are lists of "What do I need to do?".
            // We APPLY exclusion because we don't need to work on "Not Interested" people.

            $data['today_pending_followup'] = Followup::whereHas('lead', function ($q) use ($applyUserScope, $excludeNotInterested) {
                $q->tap($applyUserScope)->tap($excludeNotInterested); // <--- Filter Applied
            })
                ->where(function ($q) {
                    $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->whereDate('next_date', $today)
                ->count();
   
           $data['delay'] = (clone $this->baseDelayQuery($isBDE, $userId))
    ->where('delay', '>=', 1)
    ->distinct('lead_id')
    ->count('lead_id');

            $data['today_delay'] = (clone $this->baseDelayQuery($isBDE, $userId))
    ->whereDate('next_date', $today)
    ->where('delay', '>=', 1)
    ->distinct('lead_id')
    ->count('lead_id');

            $data['cold_clients'] = Followup::whereIn('reason', ['call back later', 'Not pickup'])
                ->whereHas('lead', function ($q) use ($applyUserScope, $excludeNotInterested) {
                    $q->tap($applyUserScope)->tap($excludeNotInterested); // <--- Filter Applied
                })
                ->distinct('lead_id')
                ->count();

            $data['today_cold_clients'] = Followup::whereIn('reason', ['call back later', 'Not pickup'])
                ->whereDate('created_at', $today)
                ->whereHas('lead', function ($q) use ($applyUserScope, $excludeNotInterested) {
                    $q->tap($applyUserScope)->tap($excludeNotInterested); // <--- Filter Applied
                })
                ->distinct('lead_id')
                ->count();

            // --- REJECTS ---
            $data['reject_not_intersted_count'] = Lead::tap($applyUserScope)
                ->whereHas('lastFollowup', function ($q) {
                    $q->where('reason', 'Not interested');
                })
                ->count();

            // Other Stats
            $data['today_proposal'] = DB::table('prposal')->whereNotNull('lead_id')->where('user_id', $userId)->where('created_at', '>=', $today)->count();
            $data['total_proposal'] = DB::table('prposal')->whereNotNull('lead_id')->where('user_id', $userId)->count();
            $data['today_complated_followup'] = Followup::whereHas('lead')->where('user_id', $userId)->where('is_completed', 1)->where('next_date', $today)->count();
            $data['today_converted'] = Lead::where('status', 1)->whereDate('created_at', $today)->tap($applyUserScope)->count();

            // Reasons (Raw counts, no filters)
            $data['reject_wrong_info_count'] = (clone $baseFollowup)->where('reason', 'Wrong Information')->distinct('lead_id')->count();
            $data['reject_other_company_count'] = (clone $baseFollowup)->where('reason', 'Work with other company')->distinct('lead_id')->count();
            $data['today_total_reject'] = (clone $baseFollowup)->where('reason', 'Wrong Information')->whereDate('created_at', $today)->distinct('lead_id')->count();
            $data['total_reject'] = (clone $baseFollowup)
                ->whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested'])
                ->distinct('lead_id')
                ->count();
            $data['followupPaymentToday'] = (clone $baseFollowup)->where('reason', 'Payment Tomorrow')->distinct('lead_id')->count();
            $data['followupPending'] = (clone $baseFollowup)->where('is_completed', '!=', 1)->distinct('lead_id')->count();
            $data['followupCompleted'] = (clone $baseFollowup)->where('is_completed', 1)->distinct('lead_id')->count();
            $data['followupOther'] = (clone $baseFollowup)->where('reason', 'Other')->distinct('lead_id')->count();
            $data['followupInterested'] = (clone $baseFollowup)->where('reason', 'Interested')->distinct('lead_id')->count();
            $data['total_amount'] = 0;
            $data['total_revenue'] = 0;
        } else {
            // ==========================================
            //               ADMIN LOGIC
            // ==========================================

            $excludeNotInterestedAdmin = function ($q) {
                $q->whereDoesntHave('lastFollowup', function ($sq) {
                    $sq->where('reason', 'Not interested');
                });
            };

            // 1. Admin Active Work Query (Applies Filter)
            $activeAdminLeads = Lead::query()->tap($excludeNotInterestedAdmin);

            // 2. Admin Raw Query (No Filter)
            $rawAdminLeads = Lead::query();
            $data['convert_leads'] = Lead::where('status', 1)->count();
            // Active Work -> Use Filter
            $data['hot_client'] = (clone $activeAdminLeads)->where('lead_status', 1)->count();
            $data['today_hot_client'] = (clone $activeAdminLeads)->where('lead_status', 1)->whereHas('Followup', fn($q) => $q->whereDate('next_date', $today))->count();

            // Fresh -> No Filter (Requested)
            $data['fresh_lead'] = (clone $rawAdminLeads)->where('status', '!=', 1)->count();
            $data['today_fresh'] = (clone $rawAdminLeads)->where('status', '!=', 1)->where('created_at', '>=', $today)->count();

            $data['freshLead'] = Lead::whereDoesntHave('Followup')->count();
            $data['today_freshLead'] = Lead::whereDate('created_at', $today)->whereDoesntHave('Followup')->count();

            // Work Queues -> Use Filter
            $data['delay'] = (clone $this->baseDelayQuery($isBDE, $userId))
    ->where('delay', '>=', 1)
    ->distinct('lead_id')
    ->count('lead_id');

            $data['today_delay'] = (clone $this->baseDelayQuery($isBDE, $userId))
    ->whereDate('next_date', $today)
    ->where('delay', '>=', 1)
    ->distinct('lead_id')
    ->count('lead_id');

            $data['cold_clients'] = Followup::whereIn('reason', ['call back later', 'Not pickup'])
                ->whereHas('lead', fn($q) => $q->tap($excludeNotInterestedAdmin))
                ->distinct('lead_id')
                ->count();

            $data['today_cold_clients'] = Followup::whereIn('reason', ['call back later', 'Not pickup'])
                ->whereDate('next_date', $today)
                ->whereHas('lead', fn($q) => $q->tap($excludeNotInterestedAdmin))
                ->distinct('lead_id')
                ->count();

            // Main List (Filtered)
            $data['leads'] = $query->with('countries')->tap($excludeNotInterestedAdmin)->orderBy('id', 'desc')->paginate(20);

            // Historical -> No Filter
            $data['total_leads'] = Lead::count();
            $data['total_followup'] = Followup::whereHas('lead')
                ->whereNotIn('reason', ['Work with other company', 'Wrong Information', 'Not interested'])
                ->distinct('lead_id')
                ->count();
            $data['today_proposal'] = Proposal::whereNotNull('lead_id')->where('created_at', '>=', $today)->count();
            $data['total_proposal'] = Proposal::whereNotNull('lead_id')->count();

            // Rejects
            $data['reject_not_intersted_count'] = Lead::whereHas('lastFollowup', function ($q) {
                $q->where('reason', 'Not interested');
            })->count();

            // ... (Keep remaining Admin total/historical counts as they were) ...
            $data['today_created_followup'] = Followup::whereHas('lead')->distinct('lead_id')->whereDate('created_at', $today)->count();
            $data['today_followup'] = Followup::whereHas('lead')->distinct('lead_id')->whereDate('next_date', $today)->count();

            $data['yesterday_followup'] = Followup::whereHas('lead')
                ->where(function ($q) use ($yesterday) {
                    $q->whereDate('created_at', $yesterday)->orWhereDate('next_date', $yesterday);
                })
                ->distinct('lead_id')
                ->count();

            $data['last7Days_followup'] = Followup::whereHas('lead')
                ->where(function ($query) {
                    $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->orWhereBetween('next_date', [Carbon::now()->subDays(7), Carbon::now()]);
                })
                ->distinct('lead_id')
                ->count();

            $data['thisMonth_followup'] = Followup::whereHas('lead')
                ->where(function ($query) use ($startOfMonth) {
                    $query->whereBetween('created_at', [$startOfMonth, Carbon::now()])->orWhereBetween('next_date', [$startOfMonth, Carbon::now()]);
                })
                ->distinct('lead_id')
                ->count();
            $data['today_leads'] = Lead::whereDate('created_at', Carbon::today())->count();
            $data['followup_today'] = $data['today_leads'] + Followup::whereNotNull('lead_id')->whereDate('next_date', $today)->count();
            $data['today_complated_followup'] = Followup::whereHas('lead')->distinct('lead_id')->whereDate('next_date', $today)->count();

            $data['today_pending_followup'] = Followup::whereHas('lead', fn($q) => $q->tap($excludeNotInterestedAdmin))
                ->where(function ($query) {
                    $query->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->whereDate('next_date', $today)
                ->count();

            // Financials
            $firstLead = $query->first();
            $data['total_amount'] = TotalAmount::whereIn('lead_id', $query->pluck('id'))->whereDate('created_at', $today)->sum('total_amount');
            $data['total_revenue'] = $firstLead ? Payment::where('lead_id', $firstLead->id)->whereDate('created_at', $today)->sum('amount') : 0;

            $data['today_converted'] = Lead::where('status', 1)->whereDate('created_at', $today)->count();

            // Rejects
            $data['today_total_reject'] = Followup::where('reason', 'Wrong Information')->whereDate('created_at', $today)->distinct('lead_id')->count();
            $data['reject_wrong_info_count'] = Followup::where('reason', 'Wrong Information')->distinct('lead_id')->count();
            $data['reject_other_company_count'] = Followup::where('reason', 'Work with other company')->distinct('lead_id')->count();
            $data['followupPaymentToday'] = Followup::where('reason', 'Payment Tomorrow')->distinct('lead_id')->count();
            $data['total_reject'] = Followup::whereIn('reason', ['Wrong Information', 'Work with other company', 'Not interested'])
                ->distinct('lead_id')
                ->count();

            $data['followupPending'] = Followup::where('is_completed', '!=', 1)->distinct('lead_id')->count();
            $data['followupCompleted'] = Followup::where('is_completed', 1)->distinct('lead_id')->count();
            $data['followupOther'] = Followup::where('reason', 'Other')->distinct('lead_id')->count();
            $data['followupInterested'] = Followup::where('reason', 'Interested')->distinct('lead_id')->count();
        }

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

    public function reports($user)
    {
        $userRole = $user->roles()->pluck('name')->toArray();
        $data = [];

        if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole)) {
            // Retrieve all BDEs
            $bdeUsers = User::where('is_active', 1)
                ->orderBy('name', 'asc')
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['BDE', 'Business Development Intern']);
                })
                ->get();

            // Prepare arrays to hold counts
            $bdeReports = [];

            // Aggregate counts for each BDE
            foreach ($bdeUsers as $bde) {
                $bdeId = $bde->id;

                $bdeReports[] = [
                    'name' => $bde->name,
                    'role' => $bde->roles()->first()->name,
                    'image' => $bde->image,
                    'email' => $bde->email,
                    'phone' => $bde->phone_no,
                    'id' => $bde->id,
                    'assigned_leads' => Lead::whereDate('created_at', Carbon::today())
                        ->where(function ($q) use ($bdeId) {
                            $q->where('user_id', $bdeId)->orWhere('assigned_user_id', $bdeId);
                        })
                        ->count(),
                    'followups' => Followup::whereHas('lead')->whereDate('created_at', Carbon::today())->where('user_id', $bdeId)->distinct('lead_id')->count(),
                    'proposals' => Lead::whereDate('proposal_date', Carbon::today())
                        ->where('proposal', 1)
                        ->where(function ($q) use ($bdeId) {
                            $q->where('user_id', $bdeId)->orWhere('assigned_user_id', $bdeId);
                        })
                        ->count(),
                    'quotation' => Lead::whereDate('quotation_date', Carbon::today())
                        ->where('quotation', 1)
                        ->where(function ($q) use ($bdeId) {
                            $q->where('user_id', $bdeId)->orWhere('assigned_user_id', $bdeId);
                        })
                        ->count(),

                    'converted' => Lead::whereDate('created_at', Carbon::today())
                        ->where('status', 1) // Adjust according to your logic
                        ->where(function ($q) use ($bdeId) {
                            $q->where('user_id', $bdeId)->orWhere('assigned_user_id', $bdeId);
                        })
                        ->count(),
                ];
            }

            $data = [
                'users' => $bdeUsers,
                'bdeReports' => $bdeReports,
            ];
        } else {
            // For non Super-Admin/Admin users
            $data = [
                'users' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'BDE');
                })->get(),
                'bdeReports' => [], // Return an empty array for non-admins
            ];
        }

        return $data;
    }

    public function counts(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->endOfDay();

        $user = auth()->user();
        // counts
        $count = [];
        if ($user && $user->hasRole(['BDE', 'Business Development Intern'])) {
            $count['leads'] = Lead::where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])->orwhereBetween('assigned_date', [$startDate, $endDate]);
            })
                ->where('assigned_user_id', $user->id)
                ->count();
            $count['proposals'] = Lead::whereBetween('mail_date', [$startDate, $endDate])
                ->where('mail_status', 1)
                ->where('assigned_user_id', $user->id)
                ->count();
            $count['followups'] = Followup::whereHas('lead')
                ->where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->distinct('lead_id')
                ->count();
            $count['proposals'] = Lead::whereDate('proposal_date', Carbon::today())->where('proposal', 1)->where('assigned_user_id', $user->id)->count();
            $count['quotation'] = Lead::whereDate('quotation_date', Carbon::today())->where('quotation', 1)->where('assigned_user_id', $user->id)->count();
            $count['delay'] = 0;
            $count['reject'] = FollowUp::whereHas('lead')
                ->where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('reason', ['Wrong Information', 'Not interested', 'Work with other company'])->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->distinct('lead_id')
                ->count();

            $count['revenue'] = 0;
        } else {
            $count['leads'] = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
            $count['followups'] = Followup::whereHas('lead')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('lead_id')
                ->distinct()
                ->count();
            $count['proposals'] = Lead::whereBetween('proposal_date', [$startDate, $endDate])
                ->where('proposal', 1)
                ->count();
            $count['quotation'] = Lead::whereBetween('quotation_date', [$startDate, $endDate])
                ->where('quotation', 1)
                ->count();
            $count['revenue'] = 0;
            $count['delay'] = Followup::whereHas('lead')
                ->where(function ($query) {
                    $query->where('delay', 1)->orWhere('is_completed', '!=', 1);
                })
                ->whereBetween('next_date', [$startDate, $endDate])
                ->distinct('lead_id')
                ->count();
            $count['reject'] = Followup::whereHas('lead')
                ->whereIn('reason', ['Wrong Information', 'Not interested', 'Work with other company'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('lead_id')
                ->distinct()
                ->count();
        }
        return response()->json($count);
    }

    public function Status($id, $status)
    {
        $lead = Lead::find($id);
        $lead->update(['lead_status' => $status]);
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
        // Apply filters for lead_day
        if ($request->has('lead_day')) {
            $leadDay = $request->input('lead_day');
            if ($leadDay == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($leadDay == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month);
            } elseif ($leadDay == 'year') {
                $query->whereYear('created_at', Carbon::now()->year);
            } elseif ($leadDay == 'custome' && $request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [$request->input('from_date'), $request->input('to_date')]);
            }
        }

        // Paginate results
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
            return redirect()->back()->with('message', 'Service Update successfully.');
        } else {
            return abort(404, 'Work not found.');
        }
    }

    public function PrposalInvoice(Request $request, $leadId, $id = null)
    {
        $services = $id ? Work::where('client_id', $leadId)->get() : Work::where('lead_id', $leadId)->get();
        if ($request->isMethod('get')) {
            if (isset($services)) {
                $amount = $services->sum('work_price');
            }
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

            if ($request->invoice_id) {
                $invoice = ProjectInvoice::find($request->invoice_id);
            } else {
                $invoice = new ProjectInvoice();
            }
            $invoice->bank = $request->bank_details;
            $invoice->gst = $request->gst;
            $invoice->client_gst_no = $request->gst_no;
            $invoice->user_id = auth()->user()->id;
            $invoice->office = $request->office;
            $invoice->service_id = $service->id;
            $amount = $request->total_amount;
            $discount = $request->discount;
            $gstAmount = ($amount * $request->gst) / 100;
            $totalAmount = $amount + $gstAmount - $discount;
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
            $url = route('prposel.mail.view', ['leadId' => $invoice->id] + ($id ? ['id' => $id] : []));
            return $this->success('created', 'send mail', $url);
        }
    }
    public function ConvertLeads(Request $request)
    {
        // Initialize the query
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
        // Apply filters for lead_day
        if ($request->has('lead_day')) {
            $leadDay = $request->input('lead_day');
            if ($leadDay == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($leadDay == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month);
            } elseif ($leadDay == 'year') {
                $query->whereYear('created_at', Carbon::now()->year);
            } elseif ($leadDay == 'custome' && $request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [$request->input('from_date'), $request->input('to_date')]);
            }
        }

        // Paginate results
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $data['pending_amount'] = $totalAmout->total_amount - $request->amount;
            $data['invoice_id'] = $lead->prposal->id;
            if ($id) {
                $data['client_id'] = $leadId;
            } else {
                $data['lead_id'] = $leadId;
            }

            $data['payment_status'] = 'Advanced';
            if ($request->time) {
                $timePart = $request->time;
            } else {
                $timePart = Carbon::now()->format('H:i:s');
            }
            $datePart = date('Y-m-d', strtotime($request->desopite_date));
            $data['desopite_date'] = $datePart . ' ' . $timePart;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $currentYear = date('Y');
                $currentMonth = date('m');
                $storagePath = "images/{$currentYear}/{$currentMonth}/";
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move($storagePath, $fileName);
                $data['image'] = $storagePath . $fileName;
            }
            $response = Payment::create($data);
            if ($response) {
                if ($id) {
                    $lead->client_status = 1;
                    $lead->save();
                    $totalAmout->pay = $response->amount;
                    $totalAmout->balance = $totalAmout->total_amount - $response->amount;
                    $totalAmout->save();
                } else {
                    $lead->status = 1;
                    $lead->save();
                    $totalAmout->pay = $response->amount;
                    $totalAmout->balance = $totalAmout->total_amount - $response->amount;
                    $totalAmout->save();
                }
            }
            if ($id) {
                $lead = User::with('service', 'prposal', 'invoice')->findOrFail($leadId);
                $services = Work::where('client_id', $leadId)->get();
                $total = TotalAmount::where('client_id', $leadId)->where('invoice_id', $lead->prposal->id)->first();
            } else {
                // Load lead and related data
                $lead = Lead::with('category', 'service', 'prposal', 'invoice')->findOrFail($leadId);
                $services = Work::where('lead_id', $leadId)->get();
                $total = TotalAmount::where('lead_id', $leadId)->where('invoice_id', $lead->prposal->id)->first();
            }

            // return view('admin.crm.prposal.mail', compact('lead', 'services', 'total','id'));
            // Render HTML view
            $html = view('admin.crm.prposal.mail', compact('lead', 'services', 'total', 'id'))->render();

            // Debugging: Check the rendered HTML content
            if (empty($html)) {
                dd('HTML content is empty');
            }

            // Generate PDF
            try {
                $pdf = PDF::loadHTML($html);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "Proposals/pdf/{$currentYear}/{$currentMonth}";

            // Format the current date and time for uniqueness
            $dateTime = date('Ymd_His'); // Format: 20240731_153212 (YearMonthDay_HourMinuteSecond)
            $pdfPath = $directoryPath . '/' . $lead->name . '_invoice_' . $dateTime . '.pdf';

            // Ensure the directory exists, create if not
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true); // Create directory with full permissions
            }

            $pdf->save($pdfPath);

            // Email details
            $to = $lead->email;
            $subject = 'Adxventure Billing Invoice';
            $name = strtoupper($lead->name);
            $message = 'Dear <strong>' . $name . '</strong>,<br><br>' . 'Please find attached the invoice for your recent work.<br><br>' . 'Thank you for your business.';

            $pdfPath1 = 'Proposals/Adxventure.pdf';
            // File attachments
            $files = [$pdfPath, $pdfPath1];

            // Headers
            $boundary = md5(uniqid(time()));
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
            $headers .= "From: info@adxventure.com\r\n";

            // Email Body
            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $message . "\r\n";

            // Attach files
            foreach ($files as $filePath) {
                $fileName = basename($filePath);
                $fileContent = file_get_contents($filePath);
                $fileContentEncoded = chunk_split(base64_encode($fileContent));

                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: application/pdf; name=\"{$fileName}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
                $body .= $fileContentEncoded . "\r\n";
            }

            $body .= "--{$boundary}--";

            // Send email
            mail($to, $subject, $body, $headers);
            // Send email with attachment
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
            // Validate the request
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

            // Check if an invoice_id is provided for updating an existing invoice
            if ($request->invoice_id) {
                // Find the existing invoice or fail
                $invoice = ProjectInvoice::findOrFail($request->invoice_id);

                // Update the invoice details
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

                // Process the work items (create, update, or delete)
                // Get the current work IDs for the invoice
                $currentWorkIds = Work::where('invoice_id', $invoice->id)->pluck('id')->toArray();

                foreach ($request->work_id as $key => $workId) {
                    // If the work item exists in the request and in the DB, update it
                    $work = Work::where('invoice_id', $invoice->id)->where('id', $workId)->first();

                    if ($work) {
                        // If the work item exists, update it
                        $work->update([
                            'work_name' => $request->work_name[$key],
                            'work_quality' => $request->quantity[$key],
                            'work_price' => $request->price[$key],
                            'work_type' => $request->work_type[$key],
                        ]);
                    } else {
                        // If the work item doesn't exist, create a new one
                        Work::create([
                            'invoice_id' => $invoice->id,
                            'work_name' => $request->work_name[$key],
                            'work_quality' => $request->quantity[$key],
                            'work_price' => $request->price[$key],
                            'work_type' => $request->work_type[$key],
                        ]);
                    }

                    // Remove the ID from the current work IDs list (since it's in the request)
                    $currentWorkIds = array_diff($currentWorkIds, [$workId]);
                }

                // Delete any work items that were in the database but not in the request
                if (count($currentWorkIds) > 0) {
                    Work::whereIn('id', $currentWorkIds)->delete();
                }
            } else {
                // Create a new invoice if no invoice_id is provided
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

                // Process the work items (create only new records)
                foreach ($request->work_name as $key => $workName) {
                    // Create a new work entry for each work item
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
            // Catch any unexpected errors and return a JSON response
            return response()->json(
                [
                    'error' => 'Something went wrong!',
                    'message' => $e->getMessage(),
                ],
                500,
            ); // Return a 500 Internal Server Error
        }
        // Redirect to the mail view (adjust the route as needed)
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

        // Return validation errors if any
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // send validation errors
                ->withInput();
        }

        // dd($request->all());
        // Prepare the invoice data
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

        // Create the invoice
        $invoice = ProjectInvoice::create($data);
        if ($invoice) {
            foreach ($request->work_name as $key => $workName) {
                Work::create([
                    'invoice_id' => $invoice->id,
                    'work_name' => $workName,
                    'work_quality' => $request->quantity[$key], // Use $key to reference the quantity
                    'work_price' => $request->price[$key], // Use $key to reference the price
                    'work_type' => $request->work_type[$key], // Use $key to reference the work type
                ]);
            }
            return redirect()->route('crm.prposel.mail.view', ['leadId' => $invoice->id, 'id' => 1]);
        }
        // Return error response if invoice creation failed
        return response()->json(['error' => 'Invoice creation failed.']);
    }

    public function viewMail($leadId, $id = null)
    {
        $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->find($leadId);
        if ($invoice) {
            return view('admin.crm.prposal.mail_preview', compact('invoice', 'id'));
        } else {
            $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->where('lead_id', $leadId)->latest()->first();
            return view('admin.crm.prposal.mail_preview', compact('invoice', 'id'));
        }
    }

    public function mail(Request $request, $invoiceId, $id = null)
    {
        $invoice = ProjectInvoice::with('lead', 'Office', 'service', 'client')->findorfail($invoiceId);

        // Validate the request for mail and WhatsApp options
        if (empty($request->send_mail) && empty($request->send_whatsapp)) {
            $validator = Validator::make(
                $request->all(),
                [
                    'send_mail' => 'required_without_all:send_whatsapp',
                    'send_whatsapp' => 'required_without_all:send_mail',
                ],
                [
                    'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
                ],
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
        }

        // Handle custom PDF file upload
        $fileUrl = null;
        if ($request->send_custome_pdf == 1) {
            $validator = Validator::make($request->all(), [
                'custome_pdf' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            // Define file-related variables
            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "Proposals/custome/{$currentYear}/{$currentMonth}";
            $dateTime = date('Ymd_His'); // e.g., 20240731_153212

            // Check if the file is provided and handle it properly
            if ($file = $request->file('custome_pdf')) {
                $fileName = $id == 1 ? $invoice->client->email . '_proposal_' . $dateTime . '.' . $file->getClientOriginalExtension() : $invoice->lead->email . '_proposal_' . $dateTime . '.' . $file->getClientOriginalExtension();

                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0755, true);
                }

                $filePath = $directoryPath . '/' . $fileName;
                $file->move($directoryPath, $fileName);
                $fileUrl = asset($filePath); // Generate a URL for the stored file
            }
        }

        // Render HTML view
        $html = view('admin.crm.prposal.mail', compact('invoice', 'id'))->render();
        if (empty($html)) {
            return response()->json(['error' => 'HTML content is empty']);
        }

        // Generate the PDF file
        try {
            $pdf = PDF::loadHTML($html);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // Define PDF paths and directories
        $pdfPath1 = 'Proposals/Adxventure.pdf';
        $currentYear = date('Y');
        $currentMonth = date('m');
        $directoryPath = "Proposals/pdf/{$currentYear}/{$currentMonth}";
        $dateTime = date('Ymd_His');

        $pdfFileName = $id == 1 ? $invoice->client->name . '_proposal_' . $dateTime . '.pdf' : $invoice->lead->name . '_proposal_' . $dateTime . '.pdf';

        $pdfPath = $directoryPath . '/' . $pdfFileName;

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $pdf->save($pdfPath);

        // Update invoice details
        $invoice->pdf = $pdfPath;
        $invoice->invoice_no = sprintf('#00%02d/%s/%d-%02d', $invoice->id, date('M', strtotime($invoice->created_at)), date('Y', strtotime($invoice->created_at)), date('y', strtotime($invoice->created_at)) + 1);
        $invoice->save();

        if ($id != 1) {
            $lead = Lead::findOrFail($invoice->lead->id);
            $lead->quotation = 1;
            $lead->quotation_date = Carbon::now();
            $lead->save();
        }

        // Send email if requested
        if ($request->has('send_mail')) {
            // $to = 'manjeetchand01@gmail.com';
            $to = $id == 1 ? $invoice->client->email : $invoice->lead->email;
            $name = strtoupper($id == 1 ? $invoice->client->name : $invoice->lead->name);

            $subject = 'Adxventure Billing Invoice';
            $message = "Welcome to <strong>{$name}</strong>,<br><br>Excited to Start Our Journey Together!<br><br>";
            $files = [$pdfPath, $pdfPath1, $fileUrl];

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . ($boundary = md5(uniqid(time())) . "\"\r\n");
            $headers .= "From: info@adxventure.com\r\n";

            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $message . "\r\n";

            foreach ($files as $filePath) {
                if ($filePath) {
                    $fileName = basename($filePath);
                    $fileContent = file_get_contents($filePath);
                    $fileContentEncoded = chunk_split(base64_encode($fileContent));

                    $body .= "--{$boundary}\r\n";
                    $body .= "Content-Type: application/pdf; name=\"{$fileName}\"\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n";
                    $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
                    $body .= $fileContentEncoded . "\r\n";
                }
            }
            $body .= "--{$boundary}--";

            mail($to, $subject, $body, $headers);
        }

        // Send WhatsApp if requested
        if ($request->has('send_whatsapp')) {
            $phone = $id == 1 ? '91' . $invoice->client->phone_no : str_replace('-', '', $invoice->lead->phone);
            // $phone = '+919997294527';
            $name = $id == 1 ? $invoice->client->name : $invoice->lead->name;

            $api = Api::first();

            $params = [
                'recipient' => $phone,
                'apikey' => $api->key,
                'text' => "Hello {$name}, Greetings from Adxventure. Please find the attached Quotation. Thank you.",
            ];

            $apiUrl = $api->url;

            foreach ([$pdfPath, $pdfPath1, $fileUrl] as $file) {
                if ($file) {
                    $params['file'] = asset($file);
                    $queryString = http_build_query($params);
                    $url = "{$apiUrl}?{$queryString}";
                    $response = Http::get($url);
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
            })
            ->get();

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
        // dd($request->all());
        $status = $request->input('action');
        $leadIds = $request->input('selectedLeads', []);

        if ($status && !empty($leadIds)) {
            $leads = Lead::whereIn('id', $leadIds)->get();
            $leadsToUpdate = $leads->filter(function ($lead) {
                return $lead->status != 1;
            });
            $leadsToUpdateIds = $leadsToUpdate->pluck('id')->toArray();
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
            // Perform bulk assignment logic here
            foreach ($leadIds as $leadId) {
                $lead = Lead::find($leadId);
                if ($lead) {
                    $lead->assigned_user_id = $userId;
                    $lead->created_at = Carbon::today(); // Update this field as needed
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

        if ($leads->isEmpty()) {
            return abort(404, 'Leads not found');
        }
        // Initialize an empty array to hold projects
        $projects = collect();

        // Loop through each lead to get the corresponding projects
        foreach ($leads as $lead) {
            // Fetch projects for the client associated with the lead
            $clientProjects = projects::with('client', 'category', 'work')->where('client_id', $lead->client_id)->get();

            // Merge the projects into the collection
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
        // Base query with relationships
        $followups = Followup::with('lead', 'user')->whereNotNull('lead_id');
        // Filter by BDE if selected
        if ($request->has('bde')) {
            $followups->where('user_id', $request->bde);
        }

        // Filter by lead day (today, month, year, custom)
        if ($request->has('day')) {
            switch ($request->day) {
                case 'today':
                    $followups->whereDate('created_at', Carbon::today());
                    break;
                case 'month':
                    $followups->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'year':
                    $followups->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'custome': // Custom date range
                    if ($request->has('from_date') && $request->has('to_date')) {
                        $followups->whereBetween('created_at', [$request->from_date, $request->to_date]);
                    }
                    break;
            }
        }

        // Paginate the results
        $followups = $followups->paginate(20);

        // Retrieve the list of users
        $users = $this->retrieveUsers(auth()->user());

        // Return the view with follow-ups and users
        return view('admin.crm.today_followup', compact('followups', 'users'));
    }

    public function today_report(Request $request)
    {
        \Log::info($query->toSql(), $query->getBindings());
        // Return the table rows as JSON response
        return response()->json([
            'tableData' => $tableData,

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
        # 1 validate data
        $validator = Validator::make(
            $request->all(),
            [
                'message_user' => 'required|numeric',
                'sendbywhatshapp' => 'required_without_all:sendbyemail',
                'sendbyemail' => 'required_without_all:sendbywhatshapp',
            ],
            [
                'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
            ],
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            # 2 fetch lead
            $lead = Lead::findOrFail($request->message_user);
            $name = $lead->name;
            $phone = $lead->phone;
            $email = $lead->email;

            $subject = 'Adxventure Portfolio';
            $message = 'Dear ' . $name . ",\n" . "We hope this message finds you well.\n" . "Thank you,\n Adxventure\n";

            $fileUrl = asset('portfolio/adxventure_portfolio.pdf');

            # 3 send by whatshapp
            if ($request->has('sendbywhatshapp')) {
                if (!str_starts_with($phone, '+91')) {
                    $phone = explode('-', $phone);
                    $phone = '91' . $phone['1'];
                }

                $api = auth()->user()->api;
                $apiKey = $api->key;
                $whatsappApiUrl = $api->url;

                $response = Http::get($whatsappApiUrl, [
                    'recipient' => $phone,
                    'apikey' => $apiKey,
                    'text' => $message,
                    'file' => $fileUrl,
                ]);

                if (!$response->successful()) {
                    return response()->json(['error' => 'Failed to send message via WhatsApp.']);
                }
            }

            # 4 send by mail
            if ($request->has('sendbyemail')) {
                $to = $email;
                $boundary = md5(uniqid(time()));

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
                $headers .= "From: info@adxventure.com\r\n";

                $body = "--{$boundary}\r\n";
                $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $body .= nl2br($message) . "\r\n";

                $fileContent = file_get_contents($pdfPath);
                $fileContentEncoded = chunk_split(base64_encode($fileContent));

                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: application/pdf; name=\"{$pdfFileName}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$pdfFileName}\"\r\n\r\n";
                $body .= $fileContentEncoded . "\r\n";
                $body .= "--{$boundary}--";

                mail($to, $subject, $body, $headers);
            }
            return response()->json(['success' => 'Portfolio sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong !, Please try again later.']);
        }
    }
    public function messages($id)
    {
        $messages = Message::where('lead_id', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.crm.meesages', compact('messages'));
    }

    public function cutome_proposal(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'proposal_user' => 'required|numeric',
                'sendbywhatshapp' => 'required_without_all:sendbyemail',
                'sendbyemail' => 'required_without_all:sendbywhatshapp',
                'proposal_type' => 'required|numeric',
            ],
            [
                'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
            ],
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $lead = Lead::findOrFail($request->proposal_user);

            if ($lead) {
                $name = $lead->name;
                $phone = $lead->phone;

                if (!str_starts_with($phone, '+91')) {
                    $phone = explode('-', $phone);
                    $phone = '91' . $phone['1'];
                }

                // dd($phone);
                $category = Category::findorFail($lead->client_category);

                if ($category) {
                    $raw_message = $category->whatshapp_message;

                    // Convert <li> to bullet or numbered format placeholder (we handle ol vs ul below)
                    $raw_message = preg_replace_callback(
                        '/<(ol|ul)>(.*?)<\/\1>/is',
                        function ($matches) {
                            $list_type = $matches[1]; // 'ol' or 'ul'
                            $items = [];

                            preg_match_all('/<li>(.*?)<\/li>/is', $matches[2], $li_matches);

                            foreach ($li_matches[1] as $index => $item) {
                                $prefix = $list_type === 'ol' ? $index + 1 . '. ' : '• ';
                                $items[] = $prefix . trim(strip_tags($item));
                            }

                            return "\n" . implode("\n", $items) . "\n";
                        },
                        $raw_message,
                    );

                    // Convert <div>, <br>, </p>, etc. to newlines
                    $raw_message = str_ireplace(['<div>', '</div>', '<br>', '<br/>', '<br />', '</p>', '<p>'], "\n", $raw_message);

                    // Strip remaining tags
                    $formatted_message = strip_tags($raw_message);

                    // Clean up extra newlines
                    $formatted_message = preg_replace("/\n{2,}/", "\n", $formatted_message);

                    $whatshapp_message = "Hello Sir/Ma'am,\nGreetings from Adxventure.\n" . trim($formatted_message) . "\nThank you,\nAdxventure\n+91-9149214580\nhttps://adxventure.com/";

                    // dd($phone);
                    if ($request->proposal_type == 1) {
                        //send by whatshapp
                        if ($request->has('sendbywhatshapp')) {
                            $api = auth()->user()->api;
                            $apiKey = $api->key;
                            $whatsappApiUrl = $api->url;

                            $response = Http::get($whatsappApiUrl, [
                                'file' => asset($category->image),
                                'text' => $whatshapp_message,
                                'apikey' => $apiKey,
                                'recipient' => $phone,
                            ]);

                            if (!$response->successful()) {
                                return response()->json(['error' => 'Failed to send message via WhatsApp.']);
                            }
                        }

                        return response()->json(['success' => 'Message sent successfully.']);
                    } elseif ($request->proposal_type == 2) {
                        //send by whatshapp
                        if ($request->has('sendbywhatshapp')) {
                            $api = auth()->user()->api;
                            $apiKey = $api->key;
                            $whatsappApiUrl = $api->url;

                            $response = Http::get($whatsappApiUrl, [
                                'recipient' => $phone,
                                'apikey' => $apiKey,
                                'text' => $whatshapp_message,
                                'file' => asset($category->pdf),
                            ]);

                            if (!$response->successful()) {
                                return response()->json(['error' => 'Failed to send message via WhatsApp.']);
                            }
                        }
                        return response()->json(['success' => 'Message sent successfully.']);
                    }
                }

                $lead->update([
                    'proposal' => 1,
                    'proposal_date' => Carbon::now(),
                ]);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

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

    // Api Function
    public function api()
    {
        $api = Api::where('user_id', auth()->user()->id)->first();
        return view('admin.crm.api', compact('api'));
    }

    public function api_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $response = Http::post('https://wabot.adxventure.com/api/user/create-api-key', [
                'userId' => '68886051d5c622185099371d',
                'mobileNumber' => $request->number,
            ]);
            $data = $response->json();
            if ($data['status']) {
                Api::create([
                    'name' => 'whatshapp',
                    'user_id' => auth()->user()->id,
                    'url' => 'http://wabot.adxventure.com/api/user/send-media-message',
                    'key' => $data['apiKey'],
                    'phone' => $request->number,
                    'trial_ends' => $data['trialEndsAt'],
                ]);
                $success_message = $data['message'];
            } else {
                $success_message = $data['message'];
            }
            return response()->json(['success' => $success_message]);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
            // return response()->json(['errors' => $e->getMessage()]);
        }
    }
    private function baseDelayQuery($isBDE, $userId)
{
    return Followup::query()
        ->whereNull('deleted_at')
        ->where(function ($q) {
            $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
        })
        ->whereHas('lead', function ($q) use ($isBDE, $userId) {

            if ($isBDE) {
                $q->where(function ($sub) use ($userId) {
                    $sub->where('user_id', $userId)
                        ->orWhere('assigned_by', $userId)
                        ->orWhere('assigned_user_id', $userId);
                });
            }

            // 🔴 CRITICAL: Latest followup must NOT be rejected
            $q->whereDoesntHave('lastFollowup', function ($sq) {
                $sq->whereIn('reason', [
                    'Not interested',
                    'Wrong Information',
                    'Work with other company'
                ])->whereNull('deleted_at');
            });
        });
}
}
