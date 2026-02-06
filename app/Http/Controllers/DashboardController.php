<?php

namespace App\Http\Controllers;

use App\Models\{Lead, User, Projects, Followup, LateReason, Leaves};
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // --- Standard Counts (Global) ---
        $count = [
            'leads'      => Lead::whereDate('created_at', $today)->count(),
            'followups'  => Followup::whereDate('created_at', $today)->count(),
            'proposal'   => Lead::whereDate('proposal_date', $today)->count(),
            'quotation'  => Lead::whereDate('quotation_date', $today)->count(),
            'revenue'    => 0, // Implement your revenue logic here
            'employee'   => User::whereNotIn('role_id', [1, 5])->where('is_active', 1)->count(),
            'client'     => User::where('role_id', 5)->where('is_active', 1)->count(),
            'project'    => Projects::count(),
            'task'       => 0,
            'attandance' => LateReason::whereDate('created_at', $today)->where('user_id', '!=', 1)->count(),
        ];

        // --- Role Specific Logic (Role 8: Telecaller/Sales) ---
        if ($user->role_id == 8) {
            
            // 1. WORK DONE TODAY (Get IDs of leads we touched today)
            // We use this list to REMOVE them from the "Pending" count so they don't appear twice.
            $workedLeadIds = Followup::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->whereNotNull('lead_id')
                ->distinct()
                ->pluck('lead_id')
                ->toArray();

            $count['today_taken'] = count($workedLeadIds);

            // 2. PENDING TODAY (Logic from handleRoleBasedLogic)
            // Criteria:
            // - Assigned to Me
            // - Scheduled for Today
            // - Not Completed (NULL or != 1)
            // - AND Lead ID is NOT in the $workedLeadIds list (Meaning I haven't called them yet today)
            $count['today_pending'] = Followup::where('user_id', $user->id)
                ->whereDate('next_date', $today)
                ->where(function ($q) {
                    $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->whereNotIn('lead_id', $workedLeadIds) 
                ->distinct('lead_id')
                ->count();

            // 3. OVERDUE/DELAY (Optional: Items missed in the past)
            // This logic is slightly complex; usually, you just want items scheduled BEFORE today that are incomplete.
            $count['total_delay'] = Followup::where('user_id', $user->id)
                ->whereDate('next_date', '<', $today) // Scheduled in the past
                ->where(function ($q) {
                    $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->distinct('lead_id')
                ->count();

        } else {
            $count['today_taken']   = 0;
            $count['today_pending'] = 0;
            $count['total_delay']   = 0;
        }

        $leaves = Leaves::whereDate('from_date', $today)->get();

        return view('dashboard', compact('count', 'leaves'));
    }

    public function hrms() { return view('dashboard.hrms'); }
    public function crm()  { return view('dashboard.crm'); }
}