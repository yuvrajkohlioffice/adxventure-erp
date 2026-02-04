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
        $count['leads'] = Lead::whereDate('created_at', carbon::today())->count();
        $count['followups'] = Followup::whereDate('created_at', carbon::today())->count();
        $count['proposal'] = Lead::whereDate('proposal_date', carbon::today())->count();
        $count['quotation'] = Lead::whereDate('quotation_date', carbon::today())->count();
        $count['revenue'] = 0;
        $count['employee'] = User::whereNotIn('role_id', ['1,5'])->where('is_active', 1)->count();
        $count['client'] = User::where('role_id', '5')->where('is_active', 1)->count();
        $count['project'] = Projects::count();
        $count['task'] = 0;
        $count['attandance'] = LateReason::whereDate('created_at', carbon::today())->where('user_id', '!=', 1)->count();

        $leaves = Leaves::whereDate('from_date', carbon::today())->get();
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
