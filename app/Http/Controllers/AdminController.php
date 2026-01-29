<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Tasks,Projects,Roles};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function index(request $request){

        $projects = Projects::all();
        $roles = Roles::where('id','!=','1')->get();

        $data = User::query();
        
        if($request->dates){
            $date = explode(' - ',$request->dates);
            $data->whereHas('taskassign', function ($query) use ($date) {
                 $query->whereHas('taskdates',function($q) use ($date){
                     $q->whereBetween('date',$date);
                 });
                 $query->orWhere('type',1);
            });
        }
        
        if($request->name){
            $data->where('name','like','%'.$request->name.'%');
        }
        if($request->position){
            $data->where('role_id',$request->position);
        }else{
            $data->whereIn('role_id',[2,3,4]);
        }

        $data->with('role')->withCount(['taskassign' => function ($query) {
            $query->where('status','!=','4');
        }]);
        
        $data = $data->orderBy('id','desc')->paginate('20');
        
        if(count($data) > 0){
            foreach($data as $d){
                
                $totaltask = Tasks::query();
                
                if($request->dates){
                    $date = explode(' - ',$request->dates);
                    $totaltask->whereHas('taskdates',function($q) use ($date){
                             $q->whereBetween('date',$date);
                    });
                }else{
                    $totaltask->whereDate('deadline',date('Y-m-d'));
                }
                
                $totaltask->orWhere('type','1');
                $totaltask = $totaltask->where('user_id',$d->id)->count();
                
                $done = Tasks::query();
                  
                if($request->dates){
                    $date = explode(' - ',$request->dates);
                    $done->whereHas('taskdates',function($q1) use ($date){
                             $q1->whereBetween('date',$date);
                    });
                }else{
                    $done->whereDate('deadline',date('Y-m-d'));
                }
                $done->orWhere('type','1');
               
                $done = $done->where('user_id',$d->id)->where('status',4)->count();
                
                if($totaltask == 0){
                    $taskDone = "No Task";
                }elseif($done == 0){
                    $taskDone = "0";
                }else{
                    $taskDone = $done / $totaltask * 100;
                   
                }
                
                if ($taskDone >= 0 && $taskDone <= 59) {
                    $color = 'bg-danger'; // or any other color you want for this range
                } elseif ($taskDone >= 60 && $taskDone <= 80) {
                    $color = 'bg-warning';
                } elseif ($taskDone >= 80 && $taskDone <= 100) {
                    $color = 'bg-success';
                } else {
                    $color = 'bg-primary'; // Default color or handle other cases
                }
                
                $d['color'] = $color;
                    
                $d['taskdone'] = $taskDone;
            }
        }
        
        return  view('admin.reports.index',compact('data','projects','roles'));
    }

    public function tasks(Request $request,$id){
         
        return redirect()->route('project.task',$id);
        $request->userId = $id;
        $pendingDates = $this->getTaskCompletedInAMonth($request) ?? [];
        
        if($pendingDates){
            $pendingDates = array_column($pendingDates, 'date');
            $pendingDates  = json_encode($pendingDates);
        }
       
        $data = [];
        
        $userData = User::find($id);
        $request->userData = $userData;
       
        return  view('admin.user.tasks',compact('data','pendingDates'));

    }
    
    public function dates($date){
        
        if(is_null($date)){
            return NULL;
        }

        $date = explode(' - ',$date);
        
        foreach($date as $key => $d){
            $date[$key] = date('Y-m-d',strtotime($d));
        }
        
        return $date;
        
    }

    public function AllTaskTypeWise(Request $request){

        $userId = auth()->user()->id;

        $tasks = Tasks::select(
                'tasks.*', 
                DB::raw("
                    CASE
                        WHEN tasks.type = '1' THEN 'Today'
                        WHEN tasks.type = '2' AND YEARWEEK(tasks.deadline, 1) = YEARWEEK(CURDATE(), 1) THEN 'This Week'
                        WHEN tasks.type = '3' AND MONTH(tasks.deadline) = MONTH(CURDATE()) AND YEAR(tasks.deadline) = YEAR(CURDATE()) THEN 'This Month'
                        ELSE 'Other'
                    END AS TimeFrame"
                ))
            ->whereHas('users',function($query) use ($userId){
                $query->where('user_task.user_id','4');
            })
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('tasks.type', '1');
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->where('tasks.type', '2')
                            ->whereRaw("YEARWEEK(tasks.deadline, 1) = YEARWEEK(CURDATE(), 1)");
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->where('tasks.type', '3')
                            ->whereRaw("MONTH(tasks.deadline) = MONTH(CURDATE())")
                            ->whereRaw("YEAR(tasks.deadline) = YEAR(CURDATE())");
                });
            })
            ->orderBy('type','ASC')->orderBy('id','desc')->get();

            dd($tasks->toArray());
    
    }
}
