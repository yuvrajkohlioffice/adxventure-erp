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
            'revenue'    => 0,
            'employee'   => User::whereNotIn('role_id', [1, 5])->where('is_active', 1)->count(),
            'client'     => User::where('role_id', 5)->where('is_active', 1)->count(),
            'project'    => Projects::count(),
            'task'       => 0,
            'attandance' => LateReason::whereDate('created_at', $today)->where('user_id', '!=', 1)->count(),
        ];

        // --- Role Specific Logic (Role 8: Telecaller/Sales) ---
        if ($user->role_id == 8) {
            
            // 1. WORK DONE TODAY
            $workedLeadIds = Followup::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->whereNotNull('lead_id')
                ->distinct()
                ->pluck('lead_id')
                ->toArray();

            $count['today_taken'] = count($workedLeadIds);

            // 2. PENDING TODAY (Scheduled for today, not yet worked on)
            $count['today_pending'] = Followup::where('user_id', $user->id)
                ->whereDate('next_date', $today)
                ->where('is_completed', '!=', 1)
                ->whereNotIn('lead_id', $workedLeadIds)
                ->distinct('lead_id')
                ->count();

            // 3. OVERDUE/DELAY (Fixed variable name to $count)
            $count['total_delay'] = Followup::where('user_id', $user->id)
                ->whereHas('lead') // Scheduled before today
                ->where(function ($query) {
                    $query->where('delay', 1)
                          ->orWhere('is_completed', '!=', 1);
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