<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Logs};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LogsController extends Controller
{       

    public function index(Request $request,$id){

        $data = Logs::query();
        $data->with('users:id,name,role_id');
        
        // $data->whereHas('users',function($query){
        //     $query->whereNotIn('role_id',[1,5]);
        // });
        
        $data->where('user_id',$id);
        
        if($request->name){
            $data->where('name','LIKE','%'.$request->name.'%');
        }
         
        if($request->status || $request->status == "0"){
            $data->where('status',$request->status);
        }
        
        $data = $data->orderBy('id', 'desc')->paginate('20');
        

        return  view('admin.logs.index',compact('data'));
    }

    public function logs()
    {
        $login_time = Logs::where('user_id', auth()->user()->id)
                          ->where('type', 1)
                          ->whereDate('time', Carbon::today())
                          ->first();
    
        $logout_time = Logs::where('user_id', auth()->user()->id)
                           ->where('type', 2)
                           ->whereDate('time', Carbon::today())
                           ->orderBy('id', 'desc')
                           ->first();

        
dd('login time: ' . $login_time->time . ' logout time: ' . $logout_time->time??0);

    
        // You can return or process $login_time and $logout_time as needed
    }

   
}
