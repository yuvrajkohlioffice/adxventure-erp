<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use App\Models\{Tasks,User};

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($type, $message,$url = "")
    {

        switch ($type) {
            case 'created':
                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' => $message ?: 'Created Successfully.',
                    'url' => $url
                ]);
            case 'updated':
                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' => $message ?: 'Updated Successfully.',
                    'url' => $url
                ]);
            case 'deleted':
                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' => $message ?: 'Deleted Successfully.',
                    'url' => $url
                ]);
                // Add more cases for other success types if needed
            case 'error':
                return response()->json([
                    'success' => false,
                    'statusCode' => 400,
                    'message' => 'Error! Please try again after sometime.',
                    'url' => $url
                ]);
            default:
                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'message' => $message ?: 'Operation Successful.',
                    'url' => $url
                ]);
        }
    }
    
    public function dailyTasksList($request,$userId){
        
        $date = $request->start_date ?? date("Y-m-d");
        
        $dailyTask = Tasks::query();
        
        if(!$request->taskStatus){
            $dailyTask->where('status','1');
        }

        if($request->project){
            $dailyTask->whereHas('project',function($aa) use ($request){
                $aa->where('id',$request->project);
            });
        }
        
        if($request->status == "4"){
            $dailyTask->whereHas('report',function($w) use ($date){
                $w->whereDate('created_at', $date);
            });
        }elseif($request->status == "0"){
            $dailyTask->whereDoesntHave('report', function($query) use ($date) {
                $query->whereDate('created_at', $date);
            });
        }
        
        $dailyTask->whereDate('created_at','<=',$date);
        
        return $dailyTask = $dailyTask->where('type','1')->whereHas('users',function($query)  use ($userId){
                    $query->where('user_task.user_id',$userId);
        })->pluck('id')->toArray();
        
    }
    
    public function weeklyTasksList($request,$userId){
        
        $date = $request->start_date ?? date("Y-m-d");
        
        $weeklyTask = Tasks::query();
        
        $weeklyTask->whereHas('users',function($query) use ($userId){
            $query->where('users.id',$userId);
        });
        
        if(!$request->taskStatus){
            $weeklyTask->where('status','1');
        }
        
        if($request->project){
            $weeklyTask->whereHas('project',function($aa1) use ($request){
                $aa1->where('id',$request->project);
            });
        }
        
        return $weeklyTask =  $weeklyTask->where('type','!=','1')
                        ->whereHas('taskdates', function ($query1) use ($date,$request) {
                            
                            if($request->status == "4"){
                                 $query1->whereDate('date', $date)->whereHas('report');
                            }elseif($request->status == "0"){
                               $query1->whereDate('date', $date)->doesntHave('report');
                            }else{
                                 $query1->whereDate('date', $date);
                            }
                                   
                            // $query1->whereDate('date', $date);
                            // ->whereHas('report', function ($query1) {
                             //           $query1->where('user_id', auth()->user()->id);
                            //       });
        })->pluck('id')->toArray();
        
    }
    
    public function mergeTasksList($mergedCollection,$request){
        
        $date = $request->start_date ?  date("Y-m-d",strtotime($request->start_date)) : date('Y-m-d');
        
        $data = Tasks::query();
        
        $data->with('dailyTask','project','organiser:id,name');
        
        $userId = $request->userId ?? auth()->user()->id;
        
        $data->with(['report' => function ($query12) use ($date,$userId) {
                $query12->whereDate('submit_date',$date);
                $query12->where('user_id',$userId);
        }]);
        
        $data->with(['taskdatestiming' => function ($query) use ($request,$date) {
            $query->whereDate('date',$date)->with('tasktiming')->orderBy('id', 'desc');
        }])->first();
    
        $data = $data->whereIn('id',$mergedCollection)->orderBy('created_at','desc')->paginate('1000');
        
        // if($date ==  "2024-03-21"){
        //     dd($data->toArray(),$date);
        // }
         
        return $data;
        
    }
    
    public function getTaskCompletedInAMonth($request){

        $userId = $request->userId ?? auth()->user()->id;
    
        $data = User::query();
        $data->whereNotIn('role_id',[1]);
        $data->where('id',$userId);
        $data->with('tasks');
        $userData = $data->first();
        
        $year = date('Y');
        $month = date('m');
        
        $dates = collect(range(1, date('d')))->map(function ($day) use ($year, $month) {
            return Carbon::create($year, $month, $day)->toDateString();
        });

        $pendingDates = [];

        foreach ($dates as $date) {
                
                $request['start_date'] = $date;
                
                $dailyTask = $this->dailyTasksList($request,$userData->id);
                $weeklyTask = $this->weeklyTasksList($request,$userData->id);
                $mergedCollection = array_merge($dailyTask,$weeklyTask);
                $data = $this->mergeTasksList($mergedCollection,$request);
                
                $totalAssignedTasks = $data->count();
                $totalCompletedTasks = $data->whereNotNull('report')->count();
                
                    if ($totalAssignedTasks == "0") {
                       
                    } elseif ($totalCompletedTasks == "0") {
                        $pendingDates[] = [
                            'date' => $date,
                            'totalAssignedTasks' => $totalAssignedTasks,
                            'totalCompletedTasks' => $totalCompletedTasks
                        ];
                    } elseif($totalAssignedTasks == $totalCompletedTasks ){
                        
                    }else {
                        $pendingDates[] = [
                            'date' => $date,
                            'totalAssignedTasks' => $totalAssignedTasks,
                            'totalCompletedTasks' => $totalCompletedTasks
                        ];
                    }

        }
        

        return $pendingDates;

        $pendingDates = json_encode($pendingDates);

        $pendingDates = json_decode($pendingDates,true);
        

        return view('admin.reports.monthlyReport',compact('pendingDates'));
        
        
    }
    
    public function formatDate($dateString){
         if(!$dateString){
             return NULL;
         }
         return  Carbon::createFromFormat('d-m-Y', trim($dateString))->format('Y-m-d');
    }
    
    
}
