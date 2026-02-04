<?php

namespace App\Http\Controllers;
use App\Models\{Invoice,User,Work,Payment,ProjectCategory,Lead,Category,TotalAmount,Projects,Followup};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        return view('admin.users.index');
    }
    
    // public function profileView(){
    //     return view('admin.profile.create');
    // }
    
    // public function profileChange(Request $request){
        
    // }


    
    public function get_lead(Request $request){
        $filter = $request->get('filter');
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['lead_count' => 0]);
        }
    
        $query = Lead::query();
        if ($user->hasRole('BDE')) {
            $query->where('user_id', $user->id);
        }
    
        switch ($filter) {
            case 1: // Today
                $query->whereDate('created_at', Carbon::today());
                break;
    
            case 2: // Current Month
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
    
            case 3: // Current Year
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }
    
        $lead_count = $query->count();
    
        return response()->json(['lead_count' => $lead_count]);
    }
    


    public function get_followup(Request $request){
        $filter = $request->get('filter');
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['followup_count' => 0]);
        }
    
        $query = Followup::whereNotNull('lead_id');
        
        if ($user->hasRole('BDE')) {
            $query->where('user_id', $user->id);
        }
        
        switch ($filter) {
            case 1: // Today
                $query->whereDate('created_at', Carbon::today());
                break;
            case 2: // Current Month
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 3: // Current Year
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            default:
                $query->whereDate('created_at', Carbon::today()); // Default case if needed
        }
        
        $followup_count = $query->count();
        
        return response()->json(['followup_count' => $followup_count]);
    }
        



    public function get_proposal(Request $request){
        $filter = $request->get('filter');
 
        $proposal_count = 0;

       
        switch ($filter) {
            case 1: 
                $proposal_count =DB::table('prposal')->whereDate('created_at', Carbon::today())->count();
                break;
            case 2: 
                $proposal_count =DB::table('prposal')->whereMonth('created_at', Carbon::now()->month)->count();
                break;
            case 3:
                $proposal_count = DB::table('prposal')->whereYear('created_at', Carbon::now()->year)->count();
                break;
        }
        return response()->json(['proposal_count' => $proposal_count]);
    }
}
