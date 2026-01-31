<?php

namespace App\Http\Controllers;

use App\Models\{User, Projects, Followup, LateReason, Leaves, lead};
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // 1. Parse Dates (Default to Today if empty)
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->startOfDay();
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)->endOfDay()   : Carbon::today()->endOfDay();

        // 2. Optimized Queries using whereBetween
        $count = [];
        $count['leads']     = lead::whereBetween('created_at', [$startDate, $endDate])->count();
        $count['followups'] = Followup::whereBetween('created_at', [$startDate, $endDate])->count();
        $count['proposal']  = lead::whereBetween('proposal_date', [$startDate, $endDate])->count();
        $count['quotation'] = lead::whereBetween('quotation_date', [$startDate, $endDate])->count();
        $count['revenue']   = 0; // Add your revenue logic here

        // Global Counts (Not filtered by date)
        $count['employee']  = User::whereNotIn('role_id', ['1', '5'])->where('is_active', 1)->count();
        $count['client']    = User::where('role_id', '5')->where('is_active', 1)->count();
        $count['project']   = Projects::count();
        $count['task']      = 0; 

        // Attendance (Filtered)
        $count['attandance'] = LateReason::whereBetween('created_at', [$startDate, $endDate])
                                ->where('user_id', '!=', 1)
                                ->count();

        // 3. Leaves (Filtered)
        $leaves = Leaves::with('users')
                    ->where(function($q) use ($startDate, $endDate) {
                        $q->whereBetween('from_date', [$startDate, $endDate])
                          ->orWhereBetween('to_date', [$startDate, $endDate]);
                    })
                    ->get();

        // 4. Pass string format for JS
        $start_date_str = $startDate->format('Y-m-d');
        $end_date_str   = $endDate->format('Y-m-d');

        return view('dashboard', compact('count', 'leaves', 'start_date_str', 'end_date_str'));
    }


    public function hrms(){
        return view('dashboard.hrms');
    }

    public function crm(){
        return view('dashboard.crm');
    }
}