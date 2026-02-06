<?php

namespace App\Http\Controllers;

use App\Models\{Lead, User, Projects, Followup, LateReason, Leaves};
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected function baseDelayQuery($isBDE, $userId)
    {
        return Followup::whereHas('lead', function ($q) use ($isBDE, $userId) {
            if ($isBDE) {
                $q->where(function ($sub) use ($userId) {
                    $sub->where('user_id', $userId)
                        ->orWhere('assigned_by', $userId)
                        ->orWhere('assigned_user_id', $userId);
                });
            }

            $q->whereDoesntHave('lastFollowup', function ($sq) {
                $sq->whereIn('reason', [
                    'Not interested',
                    'Wrong Information',
                    'Work with other company'
                ]);
            });
        })
            ->where(function ($q) {
                $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
            });
    }
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
        // --- Role Specific Logic (Role 8: Telecaller/Sales) ---
        if ($user->role_id == 8) {

            // Leads worked today
            $workedLeadIds = Followup::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->pluck('lead_id')
                ->toArray();

            $count['today_taken'] = count(array_unique($workedLeadIds));

            // Pending today
            $count['today_pending'] = Followup::whereDate('next_date', $today)
                ->where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->whereNotIn('lead_id', $workedLeadIds)
                ->distinct('lead_id')
                ->count();

            // ✅ DELAY (EXACT SAME LOGIC AS CRM)
            $count['total_delay'] = (clone $this->baseDelayQuery(true, $user->id))
                ->where('delay', '>=', 1)
                ->whereNotIn('lead_id', $workedLeadIds)
                ->distinct('lead_id')
                ->count('lead_id');
        } else {
            $count['today_taken']   = 0;
            $count['today_pending'] = 0;
            $count['total_delay']   = 0;
        }

        $leaves = Leaves::whereDate('from_date', $today)->get();

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
