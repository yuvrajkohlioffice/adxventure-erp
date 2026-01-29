<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\{Invoice,User,Work,Payment,Projects,Bank,Followup,Leaves,Office,ProjectInvoice,Template};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminInvoice extends Controller
{
    public function get_invoice(Request $request)
    {
        $client_id = $request->clientId;
        $projects = Projects::where('client_id', $client_id)->select('id', 'name')->get();
        $data = [];
        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->id,
                'name' => $project->name
            ];
        }
        return response()->json(['projects' => $data]);
    }

    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Retrieve templates and banks
        $templates = Template::where('type', 3)->orderBy('title', 'asc')->get();
        $banks = Bank::orderBy('bank_name', 'asc')->get();
    
        // Base query with relationships
        $query = ProjectInvoice::with(['client', 'project', 'payment', 'Followup', 'proposal', 'service', 'services', 'Office', 'lead'])
            ->orderBy('id', 'desc');
    
        // Apply filters to the query
        $this->applyFilters($query, $request);
    
        // Apply additional filter based on 'filter' type
        if ($filter) {
            switch ($filter) {
                case 'today_invoice':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'today_followup':
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
            }
        }

        // Paginate results
        $data = $query->paginate(10);
        // Additional data retrieval
        $clients = User::where('role_id', 5)->get();
        $offices = Office::get();
    
        // Monthly sales statistics and chart data preparation
        $monthlySales = ProjectInvoice::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, 
            COUNT(CASE WHEN client_id IS NOT NULL THEN 1 END) as upsale, 
            COUNT(CASE WHEN lead_id IS NOT NULL THEN 1 END) as freshsale')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Prepare chart data
        $months = $monthlySales->pluck('month');
        $freshsale = $monthlySales->pluck('freshsale');
        $upsale = $monthlySales->pluck('upsale');
    
        // Invoice summary data based on date range or today
        $summaryData = $this->getInvoiceSummaryData($startDate, $endDate);


        // Total Counts
    
        $todayFollowup = Followup::whereNotNull('invoice_id')->whereDate('created_at', Carbon::today())->count();
        $todayBilling  = ProjectInvoice::whereDate('billing_date', Carbon::today())->count();
        $todayReminderCount = ProjectInvoice::whereHas('payment', function ($reminderQuery) {
            $reminderQuery->whereDate('next_billing_date', now()->toDateString());
        })->count();
    
           // Initialize variables
        $invoiceCount = 0;
        $invoiceAmount = 0;
        $freshInvoiceCount = 0;
        $freshInvoiceAmount = 0;
        $upsaleInvoiceCount = 0;
        $upsaleInvoiceAmount = 0;

        
        // counts 
        $totalInvoice = $query->count();
        $totalInvoicePrice  = $query->sum('total_amount');
        $totalInvoiceBalance = $query->sum('balance');
        $totalInvoicePay = $query->sum('pay_amount');
        $totalGstAmount = $query->sum('gst_amount');


        if($startDate && $endDate){
            $startDate = Carbon::parse($startDate)->startOfDay(); // 2025-03-17 00:00:00
            $endDate = Carbon::parse($endDate)->endOfDay();     // 2025-03-17 23:59:59
            // Filter invoices based on the selected date range
            $todayInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->count();
           
            $todayTotalInvoicePrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
       
    
            // Fresh Invoice
            $todayFreshSaleCount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('lead_id')->count();
            $todayFreshSaleAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('lead_id')->sum('total_amount');
    
            // UpSale Invoice
            $todayUpSaleCount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('client_id')->count();
            $todayUpSaleAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('client_id')->sum('total_amount');


            // Unpaid Invoice
            $todayUnpaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNull('pay_amount')->count();
            $todayUnpaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNull('pay_amount')->sum('total_amount');
    
            // Partial Paid Invoice
            $todayPartialPaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('pay_amount')->count();
            $todayPartialPaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('pay_amount')->sum('total_amount');
    
            // Paid Invoice
            $todayPaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->where('balance', 0)->count();
            $todayPaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->where('balance', 0)->sum('total_amount');
            
            // Total amounts
            $todayPayInvoicePrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('pay_amount');
            $todayBalancePrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('balance');
            $todayGSTPrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('gst_amount');
                    
        } else {
            // $startOfWeek = Carbon::now()->startOfWeek(); // Monday by default
            // $endOfWeek = Carbon::now()->endOfWeek(); 
            // Daily stats for today
            $todayInvoice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->count();
            $todayTotalInvoicePrice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->sum('total_amount');
    
            // Fresh Invoice
            $todayFreshSaleCount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('lead_id')->count();
            $todayFreshSaleAmount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('lead_id')->sum('total_amount');
    
            // Upsale Invoice
            $todayUpSaleCount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('client_id')->count();
            $todayUpSaleAmount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('client_id')->sum('total_amount');
    
            // Unpaid Invoice
            $todayUnpaidInvoice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNull('pay_amount')->count();
            $todayUnpaidAmount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNull('pay_amount')->sum('total_amount');
    
            // Partial Paid Invoice
            $todayPartialPaidInvoice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('pay_amount')->count();
            $todayPartialPaidAmount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->whereNotNull('pay_amount')->sum('total_amount');
    
            // Paid Invoice
            $todayPaidInvoice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->where('balance', 0)->count();
            $todayPaidAmount = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->where('balance', 0)->sum('total_amount');
    
            // Total amounts
            $todayPayInvoicePrice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->sum('pay_amount');
            $todayBalancePrice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->sum('balance');
            $todayGSTPrice = ProjectInvoice::whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereDate('created_at', '<=', Carbon::now()->endOfWeek())->sum('gst_amount');

        }

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.invoice.partials.data', compact('data','totalInvoice','totalInvoicePrice','totalInvoiceBalance','totalInvoicePay','totalGstAmount', 'todayPartialPaidInvoice','todayPartialPaidAmount','todayPaidInvoice','todayPaidAmount','todayPayInvoicePrice','todayBalancePrice','todayGSTPrice', 'todayInvoice','todayTotalInvoicePrice','todayFreshSaleCount','todayFreshSaleAmount','todayUpSaleCount','todayUpSaleAmount','todayUnpaidInvoice','todayUnpaidAmount',
                ))->render(),
                'pagination' => view('admin.invoice.partials.pagination', [
                    'data' => $data,
                    'totalInvoice'=> $totalInvoice,
                    'totalInvoicePrice'=> $totalInvoicePrice,
                    'totalInvoiceBalance'=>$totalInvoiceBalance,
                    'totalInvoicePay' =>$totalInvoicePay,
                    'totalGstAmount' =>  $totalGstAmount,
                    'todayInvoice' => $todayInvoice,
                ])->render(),

                'todayInvoice' => $todayInvoice,
                'totalInvoicePrice'=> $totalInvoicePrice,
                'todayTotalInvoicePrice' => $todayTotalInvoicePrice,
                'todayFreshSaleCount' => $todayFreshSaleCount,
                'todayFreshSaleAmount' => $todayFreshSaleAmount,
                'todayUpSaleCount' => $todayUpSaleCount,
                'todayUpSaleAmount' => $todayUpSaleAmount,
                'todayUnpaidInvoice' => $todayUnpaidInvoice,
                'todayUnpaidAmount' => $todayUnpaidAmount,
                'todayPartialPaidInvoice' => $todayPartialPaidInvoice,
                'todayPartialPaidAmount' => $todayPartialPaidAmount,
                'todayPaidInvoice' => $todayPaidInvoice,
                'todayPaidAmount' => $todayPaidAmount,
                'todayPayInvoicePrice' => $todayPayInvoicePrice,
                'todayBalancePrice' => $todayBalancePrice,
                'todayGSTPrice' => $todayGSTPrice,
            ]);
        }

        return view('admin.invoice.index', compact(
            'templates', 'banks', 'data', 'clients', 'offices', 'months', 'freshsale', 'upsale', 
            'summaryData','totalInvoice','totalInvoicePrice','totalInvoiceBalance','totalInvoicePay','totalGstAmount',
            'todayInvoice','todayTotalInvoicePrice','todayFreshSaleCount','todayFreshSaleAmount','todayUpSaleCount','todayUpSaleAmount','todayUnpaidInvoice','todayUnpaidAmount',
            'todayPartialPaidInvoice','todayPartialPaidAmount','todayPaidInvoice','todayPaidAmount','todayPayInvoicePrice','todayBalancePrice','todayGSTPrice',
            'todayFollowup','todayBilling','todayReminderCount',
        ));
    }
    
    private function applyFilters($query, Request $request)
    {
        // Name filter
        if ($request->has('name')) {
            $clientName = $request->input('name');
            $query->where(function ($q) use ($clientName) {
                $q->whereHas('client', function ($clientQuery) use ($clientName) {
                    $clientQuery->where('name', 'like', '%' . $clientName . '%')
                        ->orWhere('email', 'like', '%' . $clientName . '%')
                        ->orWhere('phone_no', 'like', '%' . $clientName . '%')
                        ->orWhere('city', 'like', '%' . $clientName . '%');
                })->orWhereHas('lead', function ($leadQuery) use ($clientName) {
                    $leadQuery->where('name', 'like', '%' . $clientName . '%')
                        ->orWhere('email', 'like', '%' . $clientName . '%')
                        ->orWhere('phone', 'like', '%' . $clientName . '%')
                        ->orWhere('city', 'like', '%' . $clientName . '%');
                });
            });
        }
    
        // Invoice day filter
        if ($request->has('invoice_day') && $request->input('invoice_day') !== 'All') {
            switch ($request->input('invoice_day')) {
                case 'Today':
                    $query->whereDate('created_at', Carbon::today());
                break;
                case 'Yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                break;
                case 'This Week':
                    $query->whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                    ->whereDate('created_at', '<=', Carbon::now()->endOfWeek());
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->has(['from_date', 'to_date'])) {
                        $fromDate = $request->input('from_date');
                        $toDate = $request->input('to_date');
                        $fromDate = Carbon::parse($fromDate)->startOfDay(); // 2025-03-17 00:00:00
                        $toDate = Carbon::parse($toDate)->endOfDay();     // 2025-03-17 23:59:59
                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    }
                    break;
            }
        }
    
        // Invoice status filter
        if ($request->has('invoice_status') && $request->input('invoice_status') !== 'All') {
            switch ($request->input('invoice_status')) {
                case 'fresh':
                    $query->whereNotNull('lead_id');
                    break;
                case 'upsale':
                    $query->whereNotNull('client_id');
                    break;
                case 'Paid':
                    $query->where('balance', 0);
                    break;
                case 'partial-paid':
                    $query->whereNotNull('pay_amount')->where('balance', '>', 0);
                    break;
                case 'un-paid':
                    $query->whereNull('pay_amount');
                    break;
            }
        }

        if($request->has('bill')){
            switch ($request->input('bill')) {
                case 'gst':
                    $query->where('gst','>=',1);
                    break;
                case 'no_gst':
                    $query->where('gst','<=',1);
                    break;
            }
        }
    
        // Reminder filter
        if ($request->input('reminder') === 'today') {
            $query->whereHas('payment', function ($reminderQuery) {
                $reminderQuery->whereDate('next_billing_date', now()->toDateString());
            });
        }
        return $query;
    }
    
    private function getInvoiceSummaryData($startDate, $endDate)
    {
        $query = ProjectInvoice::query();
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        return [
            'invoiceCount' => $query->count(),
            'invoiceAmount' => $query->sum('total_amount'),
            'freshInvoiceCount' => $query->whereNotNull('lead_id')->count(),
            'freshInvoiceAmount' => $query->whereNotNull('lead_id')->sum('total_amount'),
            'upsaleInvoiceCount' => $query->whereNotNull('client_id')->count(),
            'upsaleInvoiceAmount' => $query->whereNotNull('client_id')->sum('total_amount'),
            'unpaidInvoice' => $query->whereNull('pay_amount')->count(),
            'unpaidAmount' => $query->whereNull('pay_amount')->sum('total_amount'),
            'partialPaidInvoice' => $query->whereNotNull('pay_amount')->count(),
            'partialPaidAmount' => $query->whereNotNull('pay_amount')->sum('total_amount'),
            'paidInvoice' => $query->where('balance', 0)->count(),
            'paidAmount' => $query->where('balance', 0)->sum('total_amount'),
            'totalPayInvoicePrice' => $query->sum('pay_amount'),
            'totalBalancePrice' => $query->sum('balance'),
            'totalGSTPrice' => $query->sum('gst_amount'),
        ];
    }
    
    







    // public function index(Request $request){
    //     $templates = Template::where('type',3)->orderBy('title','asc')->get();
    //     $banks = Bank::orderBy('bank_name','asc')->get();
    //     $query = ProjectInvoice::with([
    //         'client', 'project', 'payment', 'Followup', 'proposal', 'service', 'services', 'Office', 'lead'
    //     ])->orderBy('id', 'desc');
    
    //     // Apply filters to the query
    //     $this->applyFilters($query, $request);
    //     // Paginate the filtered results
    //     $data = $query->paginate(5);
    //     $clients = User::where('role_id', 5)->get();
    //     $offices = Office::get();
    
    //     // Monthly sales statistics
    //     $monthlySales = ProjectInvoice::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, 
    //         COUNT(CASE WHEN client_id IS NOT NULL THEN 1 END) as upsale, 
    //         COUNT(CASE WHEN lead_id IS NOT NULL THEN 1 END) as freshsale')
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();
    
    //     // Prepare data for chart
    //     $months = $monthlySales->pluck('month');
    //     $freshsale = $monthlySales->pluck('freshsale');
    //     $upsale = $monthlySales->pluck('upsale');
    
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     // Initialize variables
    //     $invoiceCount = 0;
    //     $invoiceAmount = 0;
    //     $freshInvoiceCount = 0;
    //     $freshInvoiceAmount = 0;
    //     $upsaleInvoiceCount = 0;
    //     $upsaleInvoiceAmount = 0;
    
    //     if($startDate && $endDate){
    //         // Filter invoices based on the selected date range
    //         $invoiceCount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->count();
    //         $invoiceAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
    
    //         // Fresh Invoice
    //         $freshInvoiceCount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('lead_id')->count();
    //         $freshInvoiceAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('lead_id')->sum('total_amount');
    
    //         // UpSale Invoice
    //         $upsaleInvoiceCount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('client_id')->count();
    //         $upsaleInvoiceAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('client_id')->sum('total_amount');


    //         // Unpaid Invoice
    //         $todayUnpaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNull('pay_amount')->count();
    //         $todayUnpaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNull('pay_amount')->sum('total_amount');
    
    //         // Partial Paid Invoice
    //         $todayPartialPaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('pay_amount')->count();
    //         $todayPartialPaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->whereNotNull('pay_amount')->sum('total_amount');
    
    //         // Paid Invoice
    //         $todayPaidInvoice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->where('balance', 0)->count();
    //         $todayPaidAmount = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->where('balance', 0)->sum('total_amount');
            
    //         // Total amounts
    //         $todayPayInvoicePrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('pay_amount');
    //         $todayBalancePrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('balance');
    //         $todayGSTPrice = ProjectInvoice::whereBetween('created_at', [$startDate, $endDate])->sum('gst_amount');
            
    //     } else {
    //         // Daily stats for today
    //         $todayInvoice = ProjectInvoice::whereDate('created_at', Carbon::today())->count();
    //         $todayTotalInvoicePrice = ProjectInvoice::whereDate('created_at', Carbon::today())->sum('total_amount');
    
    //         // Fresh Invoice
    //         $todayFreshSaleCount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('lead_id')->count();
    //         $todayFreshSaleAmount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('lead_id')->sum('total_amount');
    
    //         // Upsale Invoice
    //         $todayUpSaleCount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('client_id')->count();
    //         $todayUpSaleAmount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('client_id')->sum('total_amount');
    
    //         // Unpaid Invoice
    //         $todayUnpaidInvoice = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNull('pay_amount')->count();
    //         $todayUnpaidAmount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNull('pay_amount')->sum('total_amount');
    
    //         // Partial Paid Invoice
    //         $todayPartialPaidInvoice = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('pay_amount')->count();
    //         $todayPartialPaidAmount = ProjectInvoice::whereDate('created_at', Carbon::today())->whereNotNull('pay_amount')->sum('total_amount');
    
    //         // Paid Invoice
    //         $todayPaidInvoice = ProjectInvoice::whereDate('created_at', Carbon::today())->where('balance', 0)->count();
    //         $todayPaidAmount = ProjectInvoice::whereDate('created_at', Carbon::today())->where('balance', 0)->sum('total_amount');
    
    //         // Total amounts
    //         $todayPayInvoicePrice = ProjectInvoice::whereDate('created_at', Carbon::today())->sum('pay_amount');
    //         $todayBalancePrice = ProjectInvoice::whereDate('created_at', Carbon::today())->sum('balance');
    //         $todayGSTPrice = ProjectInvoice::whereDate('created_at', Carbon::today())->sum('gst_amount');
    //     }
    
    //     if ($request->ajax()){
    //         return response()->json([
    //             'todayInvoice' => $invoiceCount,
    //             'todayTotalInvoicePrice' => $invoiceAmount,
    //             'todayFreshSaleCount' => $freshInvoiceCount,
    //             'todayFreshSaleAmount' => $freshInvoiceAmount,
    //             'todayUpSaleCount' => $upsaleInvoiceCount,
    //             'todayUpSaleAmount' => $upsaleInvoiceAmount,
    //             'todayPayInvoicePrice' => $todayPayInvoicePrice,
    //             'todayGSTPrice' => $todayGSTPrice,
    //             'todayBalancePrice' => $todayBalancePrice,
    //             'todayUnpaidAmount' => $todayUnpaidAmount,
    //             'todayUnpaidInvoice' => $todayUnpaidInvoice,
    //             'todayPartialPaidAmount' => $todayPartialPaidAmount,
    //             'todayPartialPaidInvoice' => $todayPartialPaidInvoice,
    //             'todayPaidAmount' => $todayPaidAmount,
    //             'todayPaidInvoice' => $todayPaidInvoice,
    //             // Add other values if necessary
    //         ]);
    //     }
    //     return view('admin.invoice.index', compact('templates','banks',
    //         'data', 'clients', 'offices', 'months', 'freshsale', 'upsale', 
    //         'todayInvoice', 'todayTotalInvoicePrice', 'todayPayInvoicePrice', 
    //         'todayFreshSaleCount', 'todayFreshSaleAmount', 'todayUpSaleCount', 
    //         'todayUpSaleAmount', 'todayBalancePrice', 'todayGSTPrice','todayUnpaidInvoice','todayUnpaidAmount','todayPartialPaidInvoice','todayPartialPaidAmount','todayPaidInvoice','todayPaidAmount'
    //     ));
    // }
    

    // private function applyFilters($query, Request $request)
    // {
    //     // dd($request->all());
    //     // Check if "All" is selected for any filters
    //     if ($request->has('invoice_day') && $request->input('invoice_day') === 'All') {
    //         // No need to filter by date if "All" is selected
    //         return $query; // Return the original query
    //     }
    
    //     if ($request->has('invoice_status') && $request->input('invoice_status') === 'All') {
    //         // No need to filter by status if "All" is selected
    //         return $query; // Return the original query
    //     }
    
    //     // Proceed with filtering if "All" is not selected
    //     if ($request->has('name')) {
    //         $clientName = $request->input('name');
    //         // Filter client and lead relationships
    //         $query->where(function ($q) use ($clientName) {
    //             // Filter by client-related fields
    //             $q->whereHas('client', function ($clientQuery) use ($clientName) {
    //                 $clientQuery->where('name', 'like', '%' . $clientName . '%')
    //                     ->orWhere('email', 'like', '%' . $clientName . '%')
    //                     ->orWhere('phone_no', 'like', '%' . $clientName . '%')
    //                     // ->orWhere('country', 'like', '%' . $clientName . '%')
    //                     ->orWhere('city', 'like', '%' . $clientName . '%');
    //             });
    //             // Filter by lead-related fields
    //             $q->orWhereHas('lead', function ($leadQuery) use ($clientName) {
    //                 $leadQuery->where('name', 'like', '%' . $clientName . '%')
    //                     ->orWhere('email', 'like', '%' . $clientName . '%')
    //                     ->orWhere('phone', 'like', '%' . $clientName . '%')
    //                     // ->orWhere('country', 'like', '%' . $clientName . '%')
    //                     ->orWhere('city', 'like', '%' . $clientName . '%');
    //             });
    //         });
    //     }
    
    //     if ($request->has('invoice_day')) {
    //         $invoiceDay = $request->input('invoice_day');
    
    //         switch ($invoiceDay) {
    //             case 'month':
    //                 // Filter for the current month
    //                 $query->whereMonth('created_at', now()->month);
    //                 break;
    
    //             case 'year':
    //                 // Filter for the current year
    //                 $query->whereYear('created_at', now()->year);
    //                 break;
    
    //             case 'custome':
    //                 // Filter for custom date range
    //                 if ($request->has('from_date') && $request->has('to_date')) {
    //                     $fromDate = $request->input('from_date');
    //                     $toDate = $request->input('to_date');
    //                     $query->whereBetween('created_at', [$fromDate, $toDate]);
    //                 }
    //                 break;
    
    //             // No need for a case for "All" as it's handled at the start
    //         }
    //     }
    
    //     if ($request->has('invoice_status')) {
    //         $invoiceStatus = $request->input('invoice_status');
    //         if ($invoiceStatus !== 'All') { // Exclude filtering for "All"
    //             $query->where('status', $invoiceStatus);
    //         }

    //         if($invoiceStatus == 'fresh'){
    //             $query->whereNotNull('lead_id');

    //         }elseif ($invoiceStatus == 'upsale') {
    //             $query->whereNotNull('client_id');

    //         }elseif($invoiceStatus == 'Paid'){

    //         }
    //         elseif($invoiceStatus == 'partial-paid'){
    //         }
    //         elseif($invoiceStatus == 'un-paid'){
    //         }
    //     }

    //     if ($request->has('reminder')) {
    //         $reminder = $request->input('reminder');
    //         if ($reminder === 'today') {
    //             $query->whereHas('payment', function ($reminderQuery) {
    //                 $reminderQuery->whereDate('next_billing_date', today());
    //             });
    //         }
    //     }

    //     return $query; // Return the modified query
    // }
    

    // private function applyFilters($query, Request $request)
    // {
    //     if ($request->has('name')) {
    //         $clientName = $request->input('name');
    //         $query->where(function ($q) use ($clientName) {
    //             $q->where('name', 'like', '%' . $clientName . '%')
    //                 ->orWhere('email', 'like', '%' . $clientName . '%')
    //                 ->orWhere('phone', 'like', '%' . $clientName . '%')
    //                 ->orWhere('country', 'like', '%' . $clientName . '%')
    //                 ->orWhere('city', 'like', '%' . $clientName . '%');
    //         });
    //     }

    //     if($request->has('country')){
    //         $query->where('country', $request->input('country'));
    //     }
    
    //     if ($request->has('lead_day')) {
    //         $this->applyLeadDayFilter($query, $request->input('lead_day'), $request);
    //     }
    
    //     if ($request->has('lead_status')) {
    //         $status = $request->input('lead_status');
    //         if($status == 7){
    //             $query->where('status', $status !== null ? 1 : 0);
    //         }else{
    //             $query->where('lead_status', $status !== null ? $status : 0);
    //         }
    //     }
    
    //     if ($request->has('category')) {
    //         $query->where('client_category', $request->input('category'));
    //     }
    
    //     if ($request->has('service')) {
    //         $services = $request->input('service');
            
    //         // Convert service IDs to integers
    //         if (is_array($services)) {
    //             $services = array_map('intval', $services);
    //         } else {
    //             $services = intval($services);
    //         }
    
    //         // Apply the filter with the adjusted service IDs
    //         if (is_array($services)) {
    //             $query->where(function ($q) use ($services) {
    //                 foreach ($services as $service) {
    //                     $q->orWhereJsonContains('project_category', $service);
    //                 }
    //             });
    //         } else {
    //             $query->whereJsonContains('project_category', $services);
    //         }
    //     }

    //     if($request->has('proposal')){
    //         $this->applyProposalFilter($query, $request->input('proposal'), $request);
    //     }

    //     if($request->has('followup')){
    //         $this->applyFollowupFilter($query, $request->input('followup'), $request);
    //     }
    // }
    

    public function createInvoice(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'project_id' => 'required',
            'date' => 'required|date',
            'type' => 'required',
            'gst' => 'required',
            'bank_details' => 'required',
            'office' => 'required',
            'work_name.*' => 'required',
            'quantity.*' => 'required',
            'price.*' => 'required',
            'work_type.*' => 'required',
            'discount' => 'required',
            'subtotal_value' => 'required',
            'gst_value' => 'required',
            'total_value' => 'required',
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Prepare the invoice data
        $data = [
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'billing_date' => date('Y-m-d', strtotime($request->date)),
            'office' => $request->office,
            'bank' => $request->bank_details,
            'gst' => $request->gst,
            'type' => $request->type,
            'discount' => $request->discount,
            'subtotal_amount' => $request->subtotal_value,
            'gst_amount' => $request->gst_value,
            'total_amount' => $request->total_value,
        ];
    
        // Create the invoice
        $invoice = ProjectInvoice::create($data);
        $invoice->balance = $request->total_value;
        $invoice->currency = $request->currency;
        $invoice->save();
    
        // Check if the invoice was successfully created
        if ($invoice) {
            // Iterate over work entries and save them
            foreach ($request->work_name as $index => $work_name) {
                Work::create([
                    'invoice_id' => $invoice->id,
                    'work_name' => $work_name,
                    'work_quality' => $request->quantity[$index],  // Assuming 'work_quantity' is correct
                    'work_price' => $request->price[$index],
                    'work_type' => $request->work_type[$index],
                ]);
            }
            return redirect()->route('prposel.mail.view', ['leadId' => $invoice->id, 'id' => 1])
                ->with('message', 'Invoice Created Successfully.');
        }
    
        // Return error response if invoice creation failed
        return response()->json(['error' => 'Invoice creation failed.'], 500);
    }
    
    

    

    

    public function edit($id){
        $client = Invoice::findOrFail($id);
        return view('admin.invoice.edit', compact('data'));
    }

    public function delete($id){
        $client = Invoice::find($id)->delete();
        return back()->with('message','Deleted Successfully.');
    }

    
    public function editinvoice($id){
        $client = Invoice::findOrFail($id);
        $work = Work::where('client_id', '=', $client->client->id)->orderBy('id', 'DESC')->get();
        return view('admin.invoice.edit', compact('work', 'client'));
    }

    
    public function UpdateInvoice(Request $request){
        $validator = Validator::make($request->all(),[
            'client_id' => 'required',
            'id' => 'required',
            'date' => 'required|date',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->all();
        $data['in_date'] = date('Y-m-d',strtotime($request->date));
        $data['project_id'] = $request->project_id;
        $data['gst'] = 18;
        $response = Invoice::find($request->id)->update($data);
        if($response){
            $url = url('/invoice');
            return $this->success('updated','Invoice ',$url);
        }
        return $this->success('error','Invoice ');
    }



    public function gnerateInvoice(Request $request,$id){
        $invoice = Invoice::find($id);
        if($request->bank_details){
            $invoice->bank = $request->bank_details;
        }
        $invoice->save();
        $client = Invoice::with('bank')->findOrFail($id);
        $payments = Payment::where('invoice_id', $id)->get();

        $partial_or_paid_payments = $payments->filter(function ($payment) {
            return in_array($payment->payment_status, ['Partial', 'Paid']);
        }); 
        
        $advanced_payments = $payments->where('payment_status', 'Advanced');
        $pay_amount = $partial_or_paid_payments->sum('amount');
        $advanced = $advanced_payments->sum('amount');
     
        $client->update(['gst' => ($request->gst_price ?? $client->gst) ]);
        $works = Work::where('invoice_id',$id)->orderBy('id','desc')->get();
        if($request->invoice_type == "paid"){
            return view('admin.invoice.invoices', compact('client','works','advanced','pay_amount','payments'));
        }
       return view('admin.invoice.generate', compact('client','works','advanced','pay_amount'));
    }

    // public function InvoiceView(Request $request,$id){
    //    $client = Invoice::findOrFail($id);
    //    $works = Work::where('invoice_id',$id)->orderBy('id','desc')->get();
    //    return view('admin.invoice.invoices', compact('client','works'));
    // }



    
    public function InvoiceView(Request $request,$id){
        $invoice = ProjectInvoice::with(['client', 'project', 'payment', 'Followup','proposal','service','services','Office','lead'])->findorfail($id);
        return view('admin.invoice.invoices', compact('invoice'));
    }

    public function workIndex($id){
        $data = Work::where('invoice_id',$id)->orderBy('id','desc')->get();
        return view('admin.invoice.work.index', compact('data','id'));
    }

    

    public function workStore(Request $request){
        $validator = Validator::make($request->all(), [
            'work_name' => 'required',
            'work_quality' => 'required|numeric',
            'work_price' => 'required|numeric',
            'work_type' => 'required',
            'invoice_id' => 'required'
        ]);


    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $total_amount = $request->work_price * $request->work_quality;

        $work = new Work();
        $work->work_name = $request->work_name;
        $work->work_quality = $request->work_quality;
        $work->work_price = $request->work_price;
        $work->work_type = $request->work_type;
        $work->invoice_id = $request->invoice_id;
        $work->total_amount = $total_amount;
    
        $work->save();
        return redirect()->back()->with('message', 'Work added successfully.');
    }

    

    public function workDelete($id){
        $data = Work::find($id)->delete();
        return back()->with('message','Deleted Successfully');
    }


    public function paidform($id){
       $data = Invoice::findOrFail($id);
       $works = Work::where('invoice_id',$id)->orderBy('id','desc')->get();
       $payments = Payment::where('invoice_id',$id)->orderBy('id','desc')->get();
       $totalPayment = 0;
       if(count($works) > 0){
           $totalPayment1 = $works->sum('work_price');
           $donePay = $payments->sum('amount');
           $totalPayment =  $totalPayment1 - $donePay;
       }
       return view('admin.invoice.paid',compact('data','works','totalPayment'));
    }

    

    public function paid(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'invoice_id' =>'required',
            'mode' => 'required',
            'receipt_number' => 'required',
            'desopite_date' => 'required',
            'remark' => 'required',
            'amount' => 'required|max:'.$request->pending_amount,
            'pending_amount' => 'required',
            'payment_status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
         // dd($request->all());
         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Form filled incorrectly');
        }

        $invoice = Invoice::findorfail($id);
        $invoiceCreate = new Invoice();
        $invoiceCreate->client_id = $invoice->client_id;
        $invoiceCreate->project_id = $invoice->project_id;
        $invoiceCreate->gst = $invoice->gst;
        $invoiceCreate->in_date = $invoice->in_date;
        $invoiceCreate->send_date = $invoice->send_date;
        $invoiceCreate->delay_reason = $invoice->delay_reason;
        $invoiceCreate->time = $invoice->time;
        $invoiceCreate->parent_invoice_id = $id;
        if($invoiceCreate->save()){
            $data = $request->all();
            $data['pending_amount'] = ($request->pending_amount - $request->amount);
            $data['payment_status'] = $request->payment_status;
            $data['delay_reason'] = $request->reason;
            $date['invoice_id'] = $invoiceCreate->id;
            if($data['pending_amount'] == 0){
                $data['payment_status'] = "Paid";   
            }else{
                $data['payment_status'] = "Partial";
            }
            if($request->time){
                $timePart = $request->time;
            }else{
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
            if($request->pending_amount == $request->amount){
                Invoice::find($id)->update(['pay_status' => '2']);
            }else{
                Invoice::find($id)->update(['pay_status' => '3']);
            }
            if($response){
                // $url = url('/invoice');
                // return $this->success('updated','Invoice ',$url);
                return redirect()->back()->with('message','successfully payment');
            }
       }
        return redirect()->back()->with('message','From Fill Correctly');
    }


    public function paymentsIndex($id){
        $data = Payment::where('lead_id',$id)->orderBy('id','desc')->get();
        $client = Invoice::with('project','client','Bank')->where('lead_id',$id)->first();

        return view('admin.invoice.payments-view',compact('data','client'));
    }

    



  

    // public function store(Request $request)

    // {

    //     $input = $request->all();



    //     Payment::create([

    //         'invoice_id' => $input['invoice_id'],

    //         'mode' => $input['mode'],

    //         'receipt_number' => $input['receipt_number'],

    //         'desopite_date' => $input['desopite_date'],

    //         'remark' => $input['remark'],

    //         'pending_amount' => $input['pending_amount'],

    //         // 'amount' => $input['amount'],

    //     ]);



    //     if ($input['pending_amount'] == 0) 

    //     {

    //         Invoice::findOrFail($input['invoice_id'])->update(['pay_status' => 1]);

    //     }



    //     Session::flash('insert', 'Successfully Paid!');



    //     return redirect()->back();

    // }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

     

   

    public function WorkUpdate(Request $request)

    {  

        $validator = Validator::make($request->all(), [

            'id' => 'required|numeric',

            'invoice_id' => 'required',

            'work_name' => 'required',

            'work_quality' => 'required',

            'work_price' => 'required',

            'work_type' => 'required',

        ]);

    

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 400); // Return validation errors with HTTP status code 400

        }

        $work = Work::findOrFail($request->id);

        $work->update([

            'invoice_id' => $request->invoice_id,

            'work_name' => $request->work_name,

            'work_quality' => $request->work_quality,

            'work_price' => $request->work_price,

            'work_type' => $request->work_type,

        ]); 
        return redirect()->back()->with('message', 'Work Update successfully.');

    }

    public function show(){
        $todayDate = Carbon::today()->toDateString();
        
        
    }
    public function today_invoice(){

        $data = Invoice::with(['client', 'project', 'payment','Followup'])
        ->whereDate('in_date', today()->toDateString())
        ->where(function($query) {
            $query->whereNull('status')
                ->orWhere('status', '2');
        })->paginate(20);

        $projects = Projects::join('users', 'projects.client_id', '=', 'users.id')
        ->where('users.role_id', 5)
        ->select('projects.name as project_name')
        ->addSelect('users.id as user_id', 'users.name as user_name')
        ->get();

        $banks = Bank::get();
        $totalPayment = 0;
        foreach($data as $d){
            $works = Work::where('invoice_id',$d->id)->orderBy('id','desc')->get();
            $payments = Payment::where('invoice_id',$d->id)->orderBy('id','desc')->get();
            
            if(count($works) > 0){
                $totalPayment1 = $works->sum('total_amount');
                $donePay = $payments->sum('amount');
                $totalPayment =  $totalPayment1 - $donePay;
            }
        }

        //Total Invoice 
        $invoice = Invoice::get();
        $totalInvoice = $invoice->count();

        //Total Invoice Amount
        $work = Work::get();
        $totalInvoiceAmount = $work->sum('total_amount');

        //Toady Invoice 
        $todayInvoice = Invoice::whereDate('in_date', today()->toDateString())->get();
        $todayInvoiceCount =  $todayInvoice->count();

        //Today Invoice Amount 
        $todayWorkAmount =0;
        foreach($todayInvoice as  $today){
            $todayWork = Work::where('invoice_id',$today->id)->get();
            $todayWorkAmount = $todayWork->sum('total_amount');

        }

        //Total Debt 
        $debt = Invoice::where('status', 0)->whereDate('in_date', today()->toDateString())->get();
        $totalDebt = $debt->count();

        //Total Debt  Amount
        $debtAmount = 0;
        foreach($debt as $d){
            $work = Work::where('invoice_id',$d->id)->get();
            $debtAmount = $work->sum('total_amount');
        }

        //Total billing Amount & count
        $totalPay = Payment::whereDate('created_at', today()->toDateString())->get();
        $billingCount = $totalPay->count();
        $billingAmount = $totalPay->sum('amount');

        //Total Pending Amount & count
        $pending = Payment::where('pending_amount','!=','0')->whereDate('created_at', today()->toDateString())->first();
        $pendingCount = $pending->count();
        $pendingAmount = $pending->sum('pending_amount');

        return view('admin.invoice.today', compact('data', 'projects','totalPayment','banks','totalInvoice','totalInvoiceAmount','todayInvoiceCount','todayWorkAmount','totalDebt','debtAmount',
                    'billingCount','billingAmount','pendingCount','pendingAmount'));
    }

   

    // public function invoiceStatus($status,$id){
    //   $invoice = ProjectInvoice::findorfail($id);
    //   $invoice->status = $status;

    //   $invoice->save();
    //   return redirect()->back()->with('message','Invoice Status Change');
    // }
   
    public function debtInvoice(){
         // $data = Invoice::with('user')->orderBy('id', 'DESC')->paginate('50');
         $data = Invoice::with(['client', 'project','payment'])
         ->where('status','0')
         ->paginate(20);

         $projects = Projects::join('users', 'projects.client_id', '=', 'users.id')
         ->where('users.role_id', 5)
         ->select('projects.name as project_name')
         ->addSelect('users.id as user_id', 'users.name as user_name')
         ->get();
 
         $banks = Bank::get();
        
         foreach($data as $d){
             $works = Work::where('invoice_id',$d->id)->orderBy('id','desc')->get();
             $payments = Payment::where('invoice_id',$d->id)->orderBy('id','desc')->get();
             $totalPayment = 0;
             if(count($works) > 0){
                 $totalPayment1 = $works->sum('work_price');
                 $donePay = $payments->sum('amount');
                 $totalPayment =  $totalPayment1 - $donePay;
             }
         }
 
         $currentMonth = Carbon::now()->month;
         $currentYear = Carbon::now()->year;
 
         // Total Debt
         $Invoices = Invoice::where('status', '0')
             ->whereMonth('created_at', $currentMonth)
             ->whereYear('created_at', $currentYear)
             ->get();
 
         $totalDebt = 0;
         $totalInvoicesCount = $Invoices->count();
         foreach ($Invoices as $Invoice) {
             $payments = Payment::where('invoice_id', $Invoice->id)
                 ->whereMonth('created_at', $currentMonth)
                 ->whereYear('created_at', $currentYear)
                 ->get();
 
             foreach ($payments as $payment) {
                 $totalDebt += $payment->amount;
             }
         }
 
         // Total Pending and Total Billing
         $billingAmount = 0;
         $pendingAmount = 0;
         $billings = Payment::whereMonth('created_at', $currentMonth)
             ->whereYear('created_at', $currentYear)
             ->get();
 
         $totalBillingsCount = $billings->count();
         foreach ($billings as $billing) {
             $billingAmount += $billing->amount;
             $pendingAmount += $billing->pending_amount;
         }
 
         // Total Invoice
         $totalAmount = 0;
         $totalInvoices = Work::whereMonth('created_at', $currentMonth)
             ->whereYear('created_at', $currentYear)
             ->get();
 
         $totalWorksCount = $totalInvoices->count();
         foreach ($totalInvoices as $total) {
             $totalAmount += $total->work_price;
         }
 
         // Total Invoice for today
         $todayAmount = 0;
         $todayInvoices = Work::whereDate('created_at', today()->toDateString())->get();
 
         $todayWorksCount = $todayInvoices->count();
         foreach ($todayInvoices as $today) {
             $todayAmount += $today->work_price;
         }
 
 
 
 
                     // Same calculations for today
 
             // Total Debt for today
             $totalDebtToday = 0;
             $invoicesToday = Invoice::whereDate('in_date', today()->toDateString())
                 ->get();
 
             // Count of all invoices
             $totalInvoicesCount = $invoicesToday->count();
 
             $totalWorkPriceSum = DB::table('work')
             ->whereIn('invoice_id', $invoicesToday->pluck('id'))
             ->sum('work_price');
             foreach ($invoicesToday as $invoiceToday) {
                 $paymentsToday = Payment::where('invoice_id', $invoiceToday->id)
                                         ->whereDate('created_at', today()->toDateString())
                                         ->get();
 
          
 
                 foreach ($paymentsToday as $paymentToday) {
                     $totalDebtToday += $paymentToday->amount;
                 }
             }
 
             // Total Pending and Total Billing for today
             $billingAmountToday = 0;
             $pendingAmountToday = 0;
             $billingsToday = Payment::whereDate('created_at', today()->toDateString())
                                     ->get();
 
             foreach ($billingsToday as $billingToday) {
                 $billingAmountToday += $billingToday->amount;
                 $pendingAmountToday += $billingToday->pending_amount;
             }
 
             $totalPendingAmountToday = $pendingAmountToday;
             $totalBillingAmountToday = $billingAmountToday;
   
         return view('admin.invoice.debt', compact('data', 'projects','totalDebt','pendingAmount','billingAmount','totalInvoicesCount',
     'totalBillingsCount',
     'totalWorksCount','totalDebt','banks','todayWorksCount','todayAmount','totalBillingAmountToday','totalWorkPriceSum','totalInvoicesCount'));
    }
  

    public function send_invoice(Request $request,$id){
        $invoice = Invoice::find($id);
       $invoice->status = '2';
       $invoice->delay_reason = $request->reason;
       $invoice->send_date = now()->format('Y-m-d H:i:s');
       $invoice->save();
       return redirect()->back()->with('message','Invoice Sent Successfully');

    }

    public function reminder(Request $request) {
        // if ($request->type == "common") {
        //     $validator = Validator::make($request->all(), [
        //         'TemplateSendId' => 'required|numeric',
        //         'type' => 'required|string',
        //         'template' => 'required|numeric',
        //         'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048', // Allow PDF, image files, max size 2 MB
        //     ]);
        // } else {
          
        // }
    
        $validator = Validator::make($request->all(), [
            'TemplateSendId' => 'required|numeric',
            'type' => 'required|string',
            'message' => 'required|string',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048', // Allow PDF, image files, max size 2 MB
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400); // Return validation errors with HTTP status code 400
        }
    
        $invoice = ProjectInvoice::with('client', 'lead')->findorfail($request->TemplateSendId);
        if ($invoice->client) {
            $name = $invoice->client->name;
            $phone = $invoice->client->phone_no;
        } else {
            $name = $invoice->lead->name;
            $phone = $invoice->lead->phone;
        }
    
        if ($request->message) {
            $message = $request->message;
        } else {
            $template = Template::findorfail($request->template);
            $message = $template->message;
        }
    
        if (!str_starts_with($phone, '+91')) {
            $phone = '+91' . $phone;
        }
    
        $apiKey = 'EfJ3kJdXG6cz';
        $whatsappApiUrl = 'http://api.textmebot.com/send.php';
        $file = $request->file('file');
    
        if ($file) {
            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "Invoice/reminder/{$currentYear}/{$currentMonth}";
    
            // Format the current date and time for uniqueness
            $dateTime = date('Ymd_His'); // Format: 20240731_153212 (YearMonthDay_HourMinuteSecond)
            if ($invoice->client) {
                $fileName = $invoice->client->name . '_reminder_' . $dateTime . '.' . $file->getClientOriginalExtension();
            } else {
                $fileName = $invoice->lead->name . '_reminder_' . $dateTime . '.' . $file->getClientOriginalExtension();
            }
    
            // Ensure the directory exists, create if not
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true); // Create directory with full permissions
            }
    
            $filePath = $directoryPath . '/' . $fileName;
            $file->move($directoryPath, $fileName); // Move file to the correct path
            $fileUrl = 'https://tms.adxventure.com/' . $filePath;
    
            // Determine if the file is a PDF or an image
            if ($file->getClientOriginalExtension() === 'pdf') {
                // Send message with document (PDF)
                $response = Http::get($whatsappApiUrl, [
                    'recipient' => $phone,
                    'apikey' => $apiKey,
                    'text' => $message,
                    'document' => $fileUrl, // Send as document for PDFs
                ]);
            } else {
                // Send message with file (image)
                $response = Http::get($whatsappApiUrl, [
                    'recipient' => $phone,
                    'apikey' => $apiKey,
                    'text' => $message,
                    'file' => $fileUrl, // Send as file for images
                ]);
            }
        } else {
            // Send message without document or file
            $response = Http::get($whatsappApiUrl, [
                'recipient' => $phone,
                'apikey' => $apiKey,
                'text' => $message,
            ]);
        }
    
        if ($response->successful()) {
            return response()->json([
                'message' => 'WhatsApp message sent successfully!'
            ], 200);
        } else {
            return response()->json([
                'errors' => ['message' => 'Failed to send WhatsApp message.']
            ], 500);
        }
    }
    

    public function store(Request $request){
        if (empty($request->sendbyemail) && empty($request->sendbywhatshapp)) {
            $validator = Validator::make($request->all(), [
                'sendbywhatshapp' => 'required_without_all:sendbyemail',
                'sendbyemail' => 'required_without_all:sendbywhatshapp',
                'sendPaymentId' => 'required|numeric',
                'send_details' => 'required',
                'bank' =>'required|numeric',
            ], [
                'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
        }

        $invoice = ProjectInvoice::with('client', 'lead')->findorfail($request->sendPaymentId);
        if($invoice->gst >= 1){
            $bank = Bank::where('gst', 1)->first();
        }else{
            $bank = Bank::where('gst','!=',1)->first();
        }
       
        if ($invoice->client) {
            $name = $invoice->client->name;
            $phone = $invoice->client->phone_no;
            $email = $invoice->client->email;
        } else {
            $name = $invoice->lead->name;
            $phone = $invoice->lead->phone;
            $email = $invoice->client->email;
        }
            if($request->send_details === 'send_invoice_again'){
                $subject = "Invoice Details for Your Project" ;
                $message = "Dear" .$name. ",\n" .
                    "We hope this message finds you well.\n" .
                   "\nPlease find attached the invoice details for your project. Below are the key details for your reference:\n\n" .

                    "Invoice Number:"  .  $invoice->invoice_no . "\n" .
                    "Amount:"  .  $invoice->balance . "\n" .
                    "Due Date: " . Carbon::parse($invoice->billing_date)->format('d-m-Y') . "\n".
                    "Bank Account Details:"  .  $bank->bank_name . "\n\n\n" .

                    "Account Holder Name: " . $bank->holder_name . "\n" .
                    "Bank Name: " . $bank->bank_name . "\n" .
                    "Account Number: " . ($bank->account_no ?? '--') . "\n" .
                    "IFSC Code: " . ($bank->ifsc ?? '--') . "\n\n" .
                    "Should you have any questions regarding the invoice, please feel free to reach out. Thank you for the opportunity to work together, and we appreciate your timely attention to this matter.\n\n".
                    "Warm regards,\n Adxventure";

                if($request->has('sendbywhatshapp')) {
                    $phone = '91' . $phone;
                    $fileUrl = 'https://tms.adxventure.com/' . $invoice->pdf;
                    $apiKey = 'EfJ3kJdXG6cz';
                    $whatsappApiUrl = 'http://api.textmebot.com/send.php';
                    $response = Http::get($whatsappApiUrl, [
                        'recipient' => '918077226637',
                        'apikey' => $apiKey,
                        'text' => $message,
                        'document' => $fileUrl, 
                    ]);
                }
                
                if($request->has('sendbyemail')){

                }

            }elseif($request->send_details === 'send_payemnt_details'){

                $subject = "Payment Details for Your Project" ;
                $message = "Dear" .$name. ",\n" .
                 "We hope this message finds you well.\n" .

                "\nPlease find below the payment details for your project. Here’s a summary for your convenience:\n\n".
        
                "Invoice Number: " . $invoice->invoice_no   . "\n" .
                "Total Invoice Amount: " . $invoice->total_amount . "\n" .
                "Advance Received: " . ($invoice->pay_amount ?? '--') . "\n" .
                "Balance Due: " . ($invoice->balance ?? '--') . "\n" .
                "--------------------------\n" .
        
                "\nFor easy payment, please scan the QR code:\n\n" .
                "\nShould you need any further assistance or have any questions, feel free to reach out. We greatly appreciate your prompt attention and look forward to continuing our work together.\n\n" .
                "Thank you,\n Adxventure\n";
        
        
                if($request->has('sendbywhatshapp')) {
                    if (!str_starts_with($phone, '+91')) {  
                        $phone = '+91' . $phone;
                    }
                    $fileUrl = 'https://tms.adxventure.com/' . $bank->scanner;
                    $apiKey = 'EfJ3kJdXG6cz';
                    $whatsappApiUrl = 'http://api.textmebot.com/send.php';
                    $response = Http::get($whatsappApiUrl, [
                        'recipient' => $phone,
                        'apikey' => $apiKey,
                        'text' => $message,
                        'file' => $fileUrl, 
                    ]);
                }
        
                if($request->has('sendbyemail')){
                    
                }

            }elseif($request->send_details === 'send_receipt_again'){

                $payment = payment::where('invoice_id',$invoice->id)->latest()->first();
                $subject = "Invoice Details for Your Project" ;
                $message = "Dear " .$name. ",\n" .
                    "I hope this message finds you well. Thank you for your business and prompt response to our invoices. This message is a gentle reminder for the remaining balance due on your recent invoice.\n" .
                   "\nInvoice Summary:\n\n" .

                    "Total Amount:"  .  $invoice->total_amount . "\n" .
                    "Advance Received:"  .  $invoice->pay_amount . "\n" .
                    "Balance Due:"  .  $invoice->balance  . "\n\n\n" .

                    "To make the payment easier, we have attached our bank’s QR code below. You can scan it using any UPI app to complete the transaction.\n\n".
                    "Please feel free to reach out if you have any questions or require further clarification. We appreciate your timely attention to this matter.\n\n".
                    "Thank you very much!\n\n".
                    "Warm regards,\n Adxventure";

                if($request->has('sendbywhatshapp')) {
                    $phone = '91' . $phone;
                    $fileUrl = 'https://tms.adxventure.com/' . $bank->scanner;
                    $apiKey = 'EfJ3kJdXG6cz';
                    $whatsappApiUrl = 'http://api.textmebot.com/send.php';
                    //scanner and mesage
                    $response = Http::get($whatsappApiUrl, [
                        'recipient' =>  $phone ,
                        'apikey' => $apiKey,
                        'text' => $message,
                        'file' => $fileUrl, 
                    ]);
                    sleep(5);
                    // pdf
                    $response2 = Http::get($whatsappApiUrl, [
                        'recipient' => $phone,
                        'apikey' => $apiKey,
                        'document' => $payment->pdf,
                    ]);
                    if ($response->successful() && $response2->successful()) {
                       // Handle success for both responses
                    } else {
                      
                        // Handle failure if needed
                    }
                }

                if($request->has('sendbyemail')){
                    $to = $email;
                    $subject = 'Adxventure Billing Receipt';
                    $pdfPath =  $payment->pdf;
                    // HTML message content
                    $emailMessage = "Dear" .$name. ",\n" .
                    "I hope this message finds you well. Thank you for your business and prompt response to our invoices. This message is a gentle reminder for the remaining balance due on your recent invoice.\n" .
                   "\nInvoice Summary:\n\n" .

                    "Total Amount:"  .  $invoice->total_amount . "\n" .
                    "Advance Received:"  .  $invoice->pay_amount . "\n" .
                    "Balance Due:"  .  $invoice->balance  . "\n\n\n" .

                    "To make the payment easier, we have attached our bank’s QR code below. You can scan it using any UPI app to complete the transaction.\n\n".
                    "Please feel free to reach out if you have any questions or require further clarification. We appreciate your timely attention to this matter.\n\n".
                    "Thank you very much!\n\n".
                    "Warm regards,\n Adxventure";

        
                    // Boundary string for separating parts
                    $boundary = md5(uniqid(time()));
        
                    // Headers for sending HTML email with attachment
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
                    $headers .= "From: info@adxventure.com\r\n";
        
                    // Message body with boundary
                    $body = "--{$boundary}\r\n";
                    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $body .= $emailMessage . "\r\n";
        
                    // Read and encode the PDF file
                    $fileContent = file_get_contents($pdfPath);
                    $fileContentEncoded = chunk_split(base64_encode($fileContent));
        
                    // Attach the PDF file to the email
                    $body .= "--{$boundary}\r\n";
                    $body .= "Content-Type: application/pdf; name=\"{$pdfPath}\"\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n";
                    $body .= "Content-Disposition: attachment; filename=\"{$pdfPath}\"\r\n\r\n";
                    $body .= $fileContentEncoded . "\r\n";
                    $body .= "--{$boundary}--";
        
                    // Send the email
                    mail($to, $subject, $body, $headers);
                    // if () 
                   dd(1);

                }
            }

            $url = url('invoice');
            if ($response->successful()) {
                return $this->success('message','Payment link Send',$url);
            } else {
                return $this->error('message','',$url);
            }
    }
    


    // public function paymentDetailSend(Request $req){
    //     // dd($req->all());
    //     $user = Auth::User();
    //     $college_name = $user->college->name;
    //     $college_detail = College::find($user->college_id);
    //     // dd($college_detail);


    //     $rules = [
    //         'college_id__' => 'numeric|required',
    //         'student_mobile__' => 'numeric|required',
    //         'student_name__' => 'string|required',
    //         'student_id__' => 'string|required',
    //         'user_id__' => 'numeric|required',
    //         // 'email_' => 'string|required',    
    //         'course_'  => 'required|numeric',   
    //         'messagetype' => 'required',   
    //         'bank_details' => 'required',
    //     ];

    //     $messages = [
    //         'messagetype.required' => 'Please select at least one Send By Whatsapp Or Email',
    //         'bank_details.required' => 'Please select at least one Bank Details',

    //     ];
    
    //     $validator = Validator::make($req->all(), $rules, $messages);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation errors',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $courseId = $req->input('course_');
    //     // dd($courseId);

    //     $course_detail = CollegeCourseFee::where('college_id',$user->college_id)->where('course_id',$courseId)->first();
    //     if(!$course_detail){
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please Connect With Admin!, This Course Fee Not Exist In The College.'
    //         ], 500);
    //     }



 

    //     $studentName = $req->input('student_name__');
    //     $studentMobileNo = $req->input('student_mobile__');
    //     $studentEmail = $req->input('email_');
    //     $studentId    = $req->input('student_id__');
    //     $selectedBankDetails = $req->input('bank_details', []);

    //     function generateUniqueRandomString($length = 15) {
    //         return strtoupper(bin2hex(random_bytes($length / 2))); // Corrected: moved the semicolon inside the return statement
    //     }

    //     $randomString = generateUniqueRandomString(15);
    //     $formattedStudentName = strtolower(str_replace(' ', '-', $studentName));
    //     $paymentLink = route('lead.payment.show', [
    //         'studentName' => $formattedStudentName,
    //         'sname'    => $studentName,
    //         'courseId' => $courseId,
    //         'reg_fee' => $course_detail->reg_fee,
    //         'first_y_fee' => $course_detail->first_y_fee,
    //         'student_mobile__' => $studentMobileNo,
    //         'user'          => $user,
    //         'randomString' => $randomString,
    //         'college_detail' => $college_detail,
    //         'studentEmail'   => $studentEmail,
    //         'studentId'      => $studentId
    //     ]);

    //     $collegecourse = CollegeCourseFee::where('college_id',$req->college_id_)->where('course_id',$req->course)->first();


    //     $bankDetails = BankDetail::whereIn('id', $selectedBankDetails)->get();


    
     

               
    //     foreach ($bankDetails as $bankDetail) {
    //         $message .= "Bank Name: " . $bankDetail->bank_name . "\n" .
    //                     "Account Holder Name: " . $bankDetail->a_c_name . "\n" .
    //                     "Account Number: " . ($bankDetail->a_c_no ?? '--') . "\n" .
    //                     "IFSC Code: " . ($bankDetail->ifsc ?? '--') . "\n" .
    //                     "Branch Name: " . ($bankDetail->branch_name ?? '--') . "\n\n".
    //                     "--------------------------\n\n\n";

    //     }
        
    //     $message .= "\nAfter Submit a fee send your payment screenshot on +919149214580.\n\n" .
    //                 "\nIf you have any questions or need assistance, please feel free to reach out to us.\n\n" .
    //                 "Thank you,\n[$college_name]\n".
    //                 "Address : ".($college_detail->address ?? '')." \n".
    //                 "Location : ".($college_detail->location_url ?? '')." ";
    //     // dd($message);
    //     $responseText = null;

    //     if (in_array('sendbywhatsapp', $req->input('messagetype'))) {
    //         if (!str_starts_with($studentMobileNo, '+91')) {
    //             $studentMobileNo = '+91' . $studentMobileNo;
    //         }
    
    //         $apiKey = 'EfJ3kJdXG6cz';
    //         $whatsappApiUrl = 'http://api.textmebot.com/send.php';
    
    //         $responseText = Http::get($whatsappApiUrl, [
    //             'recipient' => $studentMobileNo,
    //             'apikey'    => $apiKey,
    //             'text'      => $message,
    //         ]);
    //     }
    
    //     $emailSent = false;
    //     if (in_array('sendbyemail', $req->input('messagetype'))) {
    //         $emailData = [
    //             'subject' => 'Bank Details',
    //             'studentName' => $studentName,
    //             'college_name' => $college_name,
    //             'bankDetails' => $bankDetails, 
    //             'course_detail' => $course_detail,
    //             'paymentLink'   => $paymentLink,
    //             'college_detail' => $college_detail,
    //             'user'           => $user,
              
                
                
    //         ];
    //         // dd($emailData);
    
    //         $subject = 'Bank Details';
    
    //         try {
    //             Mail::send('mail.payment-link-mail', ['emailData' => $emailData], function($message) use ($subject, $studentEmail) {
    //                 $message->to($studentEmail);
    //                 $message->subject($subject);
    //             });
    
    //             Log::channel('email')->info('Email sent successfully to ' . $studentEmail);
    //             $emailSent = true;
    //         } catch (\Exception $mailException) {
    //             \Log::error('Failed to send email to ' . $studentEmail . '. Error: ' . $mailException->getMessage());
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $mailException->getMessage()
    //             ], 500);
    //         }
    //     }


    //        // Check the responses and return appropriate success messages
    //     if ($responseText && $responseText->successful() && $emailSent) {
    //         return response()->json(['message' => 'WhatsApp message and email sent successfully!'], 200);
    //     } elseif ($responseText && $responseText->successful()) {
    //         return response()->json(['message' => 'WhatsApp message sent successfully!'], 200);
    //     } elseif ($emailSent) {
    //         return response()->json(['message' => 'Email sent successfully!'], 200);
    //     }
    //     return response()->json(['errors' => ['message' => 'Failed to send messages.']], 500);

    // }


    public function bill(Request $request,$id){
        $invoice = ProjectInvoice::with('client', 'lead')->findorfail($id);
        if($request->isMethod('get')){
            return view('admin.invoice.bill-preview',compact('invoice'));
        }else{

           // Validate the request to ensure at least one option is selected
            $validator = Validator::make($request->all(), [
                'sendbywhatshapp' => 'required_without_all:sendbyemail',
                'sendbyemail' => 'required_without_all:sendbywhatshapp',
            ], [
                'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            if($invoice->client){
                $name = $invoice->client->name;
                $email = $invoice->client->email;
                $phone = $invoice->client->phone_no;
            }else{
                $name = $invoice->lead->name;
                $email = $invoice->lead->email;
                $phone = $invoice->lead->phone;
            }


            $html = view('admin.invoice.bill',compact('invoice'))->render();
            if (empty($html)) {
                return response()->json(['error' => 'HTML content is empty']);
            }
            // Generate the PDF file
            try {
                $pdf = PDF::loadHTML($html);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }

            // Define the directory path and file name
            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "Receipt/pdf/{$currentYear}/{$currentMonth}";
            $dateTime = date('Ymd_His');
            $pdfFileName = $name . '_bill_' . $dateTime . '.pdf';
            $pdfPath = $directoryPath . '/' . $pdfFileName;

            // Create directory if it does not exist
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }
            $pdf->save($pdfPath);
            $fileUrl = 'https://tms.adxventure.com/' . $pdfPath;
            // HTML message content
            $emailMessage = "Dear <strong>{$name}</strong>,<br><br>
            We sincerely appreciate your recent payment for our services. Your timely support and trust in us mean a lot, 
            and we’re thrilled to continue our work together.<br><br>
            If you have any questions, feedback, or additional needs, please feel free to reach out. 
            Our team is always here to assist and ensure your complete satisfaction.<br><br>
            Thank you once again for your partnership and prompt payment.<br>
            Warm regards,<br>Adxventure<br><br>";

            if ($request->has('sendbyemail')) {
                $subject ="Thank You for Your Payment!";
                $to = $email;

    
                // Boundary string for separating parts
                $boundary = md5(uniqid(time()));

                // Headers for sending HTML email with attachment
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
                $headers .= "From: info@adxventure.com\r\n";

                // Message body with boundary
                $body = "--{$boundary}\r\n";
                $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $body .= $emailMessage . "\r\n";

                // Read and encode the PDF file
                $fileContent = file_get_contents($pdfPath);
                $fileContentEncoded = chunk_split(base64_encode($fileContent));

                // Attach the PDF file to the email
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: application/pdf; name=\"{$pdfFileName}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$pdfFileName}\"\r\n\r\n";
                $body .= $fileContentEncoded . "\r\n";
                $body .= "--{$boundary}--";

                // Send the email
                mail($to, $subject, $body, $headers);
            
            }

            if ($request->has('sendbywhatshapp')) {
                if (!str_starts_with($phone, '+91')) {
                    $phone = '+91' . $phone;
                }
                $apiKey = 'EfJ3kJdXG6cz';
                $whatsappApiUrl = 'http://api.textmebot.com/send.php';
                Http::get($whatsappApiUrl, [
                    'recipient' =>  $phone ,
                    'apikey' => $apiKey,
                    'text' => $emailMessage,
                    'document' => $fileUrl,
                ]);
            }
            $url = url('/invoice');
            return $this->success('success','',$url);
        }
    }  
    
    public function invoiceDetails($id){
        $invoice = ProjectInvoice::with('client','lead')->findorfail($id);
        return view('admin.invoice.invoice-details',compact('invoice'));
    }
}