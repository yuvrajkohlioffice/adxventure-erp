<?php

namespace App\Http\Controllers;

use App\Models\{Invoice, User, Work, Payment, ProjectCategory, Lead, Category, TotalAmount, Projects, Followup, LateReason, Leaves};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // --- Standard Counts for everyone ---
        $count['leads'] = Lead::whereDate('created_at', Carbon::today())->count();
        $count['followups'] = Followup::whereDate('created_at', Carbon::today())->count();
        $count['proposal'] = Lead::whereDate('proposal_date', Carbon::today())->count();
        $count['quotation'] = Lead::whereDate('quotation_date', Carbon::today())->count();
        $count['revenue'] = 0;

        $count['employee'] = User::whereNotIn('role_id', [1, 5])->where('is_active', 1)->count();
        $count['client'] = User::where('role_id', '5')->where('is_active', 1)->count();
        $count['project'] = Projects::count();
        $count['task'] = 0;
        $count['attandance'] = LateReason::whereDate('created_at', Carbon::today())->where('user_id', '!=', 1)->count();

        // --- Specific Logic for Role ID 8 (Telecaller / Sales) ---
        // --- Specific Logic for Role ID 8 (Sales/Telecaller) ---
       // --- Specific Logic for Role ID 8 (Sales/Telecaller) ---
if ($user->role_id == 8) {

    // 1. WORK DONE TODAY (Performance)
    // Get the IDs of leads you have already worked on today
    $worked_lead_ids = Followup::where('user_id', $user->id)
        ->whereDate('created_at', Carbon::today())
        ->whereNotNull('lead_id')
        ->distinct()
        ->pluck('lead_id')
        ->toArray();

    // Count how many unique leads were worked on
    $count['today_taken'] = count($worked_lead_ids);

    // 2. PENDING TODAY (My Schedule)
    // Logic: Scheduled for Today AND Not Completed AND Not Worked on yet
    $count['today_pending'] = Followup::where('user_id', $user->id)
        ->whereDate('next_date', Carbon::today()) // Scheduled for NOW
                // Not marked complete
        ->whereNotIn('lead_id', $worked_lead_ids) // <--- CRITICAL: Exclude leads we just worked on
        ->distinct('lead_id')
        ->count('lead_id');

    // 3. OVERDUE (Real Delay)
    // Logic: Scheduled BEFORE Today AND Not Completed AND Not Worked on yet
    $count['total_delay'] = Followup::where('user_id', $user->id)
        ->whereDate('next_date', '<', Carbon::today()) // Date has PASSED
        ->where('is_completed', '!=', 1)               // Not marked complete
        ->whereNotIn('lead_id', $worked_lead_ids)      // <--- CRITICAL: Exclude leads we just worked on
        ->distinct('lead_id')
        ->count('lead_id');

} else {
    $count['today_taken'] = 0;
    $count['today_pending'] = 0;
    $count['total_delay'] = 0;
}

        $leaves = Leaves::whereDate('from_date', Carbon::today())->get();

        return view('dashboard', compact('count', 'leaves'));
    }

    public function hrms()
    {
        return view('dashboard.hrms');
    }

    public function crm()
    {
        return view('dashboard.crm');
    }
}
