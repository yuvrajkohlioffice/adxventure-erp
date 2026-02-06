<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,Tasks,User,Reports,TaskDates,TaskUser,TaskTiming,ProjectUser,DailyReport};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DateTime;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public function index(Request $request, $projectId = "")
    {   
        $user = auth()->user();
        $data = Tasks::query();

        // Check user roles
        if (!$user->roles()->whereIn('id', [1, 2, 11, 12, 21])->exists()) {
            $data = $data->where('task_organiser', $user->id)
                        ->orWhereHas('users', function ($query) use ($user) {
                            $query->where('user_task.user_id', $user->id);
                        });
        }

        $data->with(['users', 'taskdates', 'organiser'])->toSql();

        // Filter by project if provided
        if ($projectId) {
            $data->whereHas('project', function ($q) use ($projectId) {
                $q->where('id', $projectId);
            });
        }

        // Filter by name if provided
        if ($request->has('name')) {
            $data->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Filter by member if provided
        if ($request->has('member')) {
            $data->whereHas('users', function ($query) use ($request) {
                $query->where('user_task.user_id', $request->member);
            });
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $data->where('status', $request->status);
        }

        // Order by ID and paginate
        $data = $data->orderBy('id', 'desc')->paginate(25);

        // Fetch the project if projectId is provided
        $project = Projects::find($projectId);

        // Process task dates
        if ($data->count() > 0) {
            foreach ($data as $d) {
                $dates = $d->taskdates->pluck('date')->toArray();
                $d->dates = implode(', ', $dates);
            }
        }

        // Fetch project members
        $projectmembers = ProjectUser::with('users')->where('project_id', $projectId)->get();

        // Return the view with data
        return view('admin.tasks.index', compact('data', 'project', 'projectmembers', 'projectId'));
    }
        
    public function taskUser(Request $request)
    {
        $id = $request->id;
        if($request->type == "hold"){
            $taskmembers = TaskUser::with('users')
            ->where('task_id', $id)
            ->get();
        }else{
        
            $taskmembers = TaskUser::with('users')
            ->where('task_id', $id)
            ->where('status','0')
            ->get();
        }
        return response()->json(['success' => true, 'taskmembers' => $taskmembers]);
    }

    


    public function create($id){
        $users = User::where('role_id','2')->get();
        $leader = User::where('role_id','4')->get();
        $manager = User::where('role_id',1)->get();
        $data = Projects::with('users')->find($id);       
        
        return  view('admin.tasks.create',compact('id','users','leader','data','manager'));
    }

    public function store(Request $request)
    {
        // Validation rules based on type
        $commonRules = [
            'project' => 'required|numeric',
            'type' => 'required|numeric',
            'name' => 'required',
            // 'category' => 'required|numeric',
            'description' => 'required',
            'estimated_time' => 'required|numeric',
        ];
        
        if ($request->type == 1) {
        } else {
            $additionalRules = [
                'assign_dates' => 'required',   
                'deadline' => 'required|date',
            ];
        }
        // Additional validation for specific roles
        if (auth()->user()->hasRole(['Technology Tech Lead', 'Technology Manager', 'Digital Marketing Manager', 'Super-Admin', 'Admin', 'Project-Manager'])) {
            $additionalRules['executive'] = 'required';
        }else{
            $additionalRules['executive'] = '';
        }

        $validator = Validator::make($request->all(), array_merge($commonRules, $additionalRules));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // $data = $request->all();
        $data['project_id'] = $request->project;
        $data['name'] = $request->name;
        $data['category'] = 3;
        $data['type'] = $request->type;
        $data['estimated_time'] = $request->estimated_time;
        $data['description'] = $request->description;
        $data['task_organiser'] = auth()->user()->id;
        $data['attachment'] = $request->has('attachment') ? 1 : 0;
        $data['remark_needed'] = $request->has('remark') ? 1 : 0;
        $data['url'] =$request->has('url') ? 1 : '0';   
        $data['deadline'] = $request->deadline;

        $response = Tasks::create($data);

        // Handle task dates if type is not 1
        if ($request->type != 1 && $request->assign_dates) {
            // Split the comma-separated dates into an array
            $dates = explode(',', $request->assign_dates);
            
            foreach ($dates as $dd) {
                // Trim any extra spaces from the date string
                $dd = trim($dd);
                // Create DateTime object from the expected format
                $date = DateTime::createFromFormat('Y-m-d', $dd);
                
                if ($date) {
                    // Format the date to 'Y-m-d'
                    $formattedDate = $date->format('Y-m-d');
                    // Create a new TaskDates record
                    $task = TaskDates::create([
                        'task_id' => $response->id,
                        'date' => $formattedDate,
                    ]);
                    // Debug output (remove or comment out in production)
                } else {
                    // Handle the case where date creation fails
                    dd('Invalid date format: ' . $dd);
                }
            }
        }
        // Handle assigning executives to the task or fallback to $response->id
        if ($request->executive) {
            foreach ($request->executive as $executiveId) {
                TaskUser::create([
                    'task_id' => $response->id,
                    'user_id' => $executiveId,
                ]);
            }
        } else {
            // If no executive is provided, assign the task to the task creator
            TaskUser::create([
                'task_id' => $response->id,
                'user_id' => auth()->user()->id,
            ]);
        }

        if ($response) {
            $url = url('/project/task/' . $request->project);
            return $this->success('created', 'Project ', $url);
        }
        return $this->success('error', 'Project ');
    }

    public function view($id){
        $project = Projects::all();
        $users = User::whereIn('role_id',[2,4])->get();
        $leader = User::where('role_id','4')->get();
        $data = Tasks::find($id);
        return  view('admin.tasks.view',compact('data','project','leader','users'));
    }

    public function edit($id){
        $users = User::where('role_id','2')->get();
        $leader = User::where('role_id','4')->get();
        $manager = User::where('role_id',1)->get();
        $data = tasks::with('taskdates', 'project', 'users')->find($id);

        // Ensure that taskdates is a valid collection and pluck the date field
        $dates = $data->taskdates->pluck('date')->toArray();
        $usersIds = collect($data->users ?? [])->pluck('id')->toArray();
        
        $aUsers = Projects::with('users')->find($data->project_id);
        
        $userDate = [];
        if($dates){
            foreach($dates as $date){
                $userDate[] = date('Y-m-d',strtotime($date));
            }
        }
        
        $userDate = implode(",",$userDate);
        $dates = ($dates) ? implode(',',$dates) : '';
    
        return  view('admin.tasks.edit',compact('data','leader','users','dates','manager','usersIds','userDate','aUsers'));
    }

    public function update(Request $request,$id){
        
        $commonRules = [
            'type' => 'required|numeric',
            'name' => 'required',
            'category' => 'required|numeric',
            'description' => 'required',
            'estimated_time' => 'required|numeric',
        ];

        if ($request->type == 1) {
        } else {
            $additionalRules = [
                'assign_dates' => 'required',   
                'deadline' => 'required|date',
            ];
        }
        // Additional validation for specific roles
        if (auth()->user()->hasRole(['Technology Tech Lead', 'Technology Manager', 'Digital Marketing Manager', 'Super-Admin', 'Admin', 'Project-Manager'])) {
            $additionalRules['executive'] = 'required';
        }else{
            $additionalRules['executive'] = '';
        }

        $validator = Validator::make($request->all(), array_merge($commonRules, $additionalRules));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }


        $data['name'] = $request->name;
        $data['category'] = $request->category;
        $data['type'] = $request->type;
        $data['estimated_time'] = $request->estimated_time;
        $data['description'] = $request->description;
        $data['task_organiser'] = auth()->user()->id;
        $data['attachment'] = $request->has('attachment') ? 1 : 0;
        $data['remark_needed'] = $request->has('remark') ? 1 : 0;
        $data['url'] =$request->has('url') ? 1 : '0';
        $data['deadline'] = $request->deadline;
        

        $response = Tasks::find($id)->update($data);
        if($request->executive){
            $executive = $request->executive;
            TaskUser::where('task_id',$id)->delete();
            foreach($executive as $dd){
                TaskUser::create([
                    'task_id' => $id,
                    'user_id' => $dd,
                ]);
            }
        }else{
            TaskUser::where('task_id',$id)->delete();
        }

        
        if ($request->type != 1 && $request->assign_dates) {
            // Split the comma-separated dates into an array
            $dates = explode(',', $request->assign_dates);
            
            foreach ($dates as $dd) {
                // Trim any extra spaces from the date string
                $dd = trim($dd);
                // Create DateTime object from the expected format
                $date = DateTime::createFromFormat('Y-m-d', $dd);
                if ($date) {    
                    // Format the date to 'Y-m-d'
                    $formattedDate = $date->format('Y-m-d');
                    
                    // Create a new TaskDates record
                    $task = TaskDates::updateOrCreate([
                        'task_id' => $id,
                        'date' => $formattedDate,
                    ]);
                    
                    // Debug output (remove or comment out in production)
                } else {
                    // Handle the case where date creation fails
                    dd('Invalid date format: ' . $dd);
                }
            }
        }
        

        if($response){
            $url = url('/project/task/'.$request->project_id);
            return $this->success('created','Project ',$url);
        }
        return $this->success('error','Project ');
    
    }

    public function destroy($id){
        $response = Tasks::find($id)->delete();
        if($response){
            return  back()->with('message','Success! tasks deleted Successfully.');
        }
        return back()->with('error','Error! Please try Again After Sometime.');

    }

    public function UserAssignTasks(Request $request, $id = null )
    {
        $request->merge(['userId' => $id]);
        // Task Function Call 
        if($request->type === "startTask"){
            $this->startdateReport($request);
        }elseif($request->type === "endTaskReport"){
            $this->store($request);
        }
        // Set default date
        $date = $request->start_date ?? date("Y-m-d");

        $projects = Projects::whereHas('users', function ($query) use ($id) {
            $query->where('users.id', isset($id) ? $id : Auth::user()->id);
        })->get(['id', 'name']);
        
        $dailyTask = $this->dailyTasks($request);
        $weeklyTask = $this->weeklyTasks($request);
        $mergedCollection = array_merge($dailyTask, $weeklyTask);
        $data = $this->mergeTasks($mergedCollection, $request);
        
        // Update request with task counts
        $request['totalTask'] = $data->count();
        $request['pendingTask'] = $data->where('report', null)->count();
        $request['doneTask'] = $data->where('report', '!=', null)->count();

        // Task 
        if(count($data) > 0){
            $userId = $request->userId ?? auth()->user()->id;
             foreach($data as $d){
                 if($d->type == 1){
                     $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id','0')->where('user_id',$userId)->whereDate('start_date',$date)->first();
                 }else{
                     $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id',$d->taskdatestiming->id)
                     ->where('user_id',$userId)->whereDate('start_date',$date)->orderBy('id','desc')->first();
                 }
                 $d['taskDate'] = $taskTiming ? $taskTiming->toArray() : null;
             }
        }

        // Project Name and Count
        $projectsTasksCount = [
            0 => [
                'name' => "All",
                'task_count' => $data->count(),
                'project_id' => 0,
            ]
        ];

        foreach ($projects  as $project) {
            $projectsTasksCount[$project->id] = [
                'name' => $this->trimToNCharacters($project->name, 10),
                'task_count' => 0,
                'project_id' => $project->id
            ];
        }

        $dailyReport = null;
        $projectData = null;
        if ($request->has('project')) {
           $dailyReport = DailyReport::where('user_id', auth()->user()->id)->where('project_id', $request->get('project'))->get(['date', 'complation_rate', 'user_id']);
        }else{
            $dailyReport = DB::table('daily_task_states')->get();
        }

        $date = empty($request->start_date) ? date('d-m-Y') : date('d-m-Y', strtotime($request->start_date));


       

        if ($request->ajax()) {
            $project = $request->project ?? '';
            $projectData = Projects::find($project);
            // Add tasks to each project
            return response()->json([
                'data' => view('admin.tasks.tabledata', compact('data', 'projectsTasksCount', 'project', 'date', 'dailyReport'))->render(),
                'totalTask' => $request['totalTask'],
                'pendingTask' => $request['pendingTask'],
                'doneTask' => $request['doneTask'],
                'projectData' => $projectData,
                'date' => $date,
                'id' => $id
            ]);
        }

        return view('admin.user.tasks', compact('projectsTasksCount', 'dailyReport','data','projectData','id'));
    }

    
    public function trimToNCharacters($string, $limit, $append = '...') {
        if (mb_strlen($string) <= $limit) {
            return $string;
        }
        $trimmed = mb_substr($string, 0, $limit);
        return $trimmed;
    }
    
    public function ReportAjaxUserAssignTasks(Request $request){
        $date = $request->start_date ?? date("Y-m-d");
      
        $dailyTask = Tasks::query();
        
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
        
        $dailyTask = $dailyTask->where('type','1')->whereHas('users',function($query){
                    $query->where('user_task.user_id',auth()->user()->id);
        })->pluck('id')->toArray();
        
        $weeklyTask = Tasks::query();
        
        if($request->project){
            $weeklyTask->whereHas('project',function($aa1) use ($request){
                $aa1->where('id',$request->project);
            });
        }

        $weeklyTask =  $weeklyTask->where('type','!=','1')
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
        
        $mergedCollection = array_merge($dailyTask,$weeklyTask);
        
        $data = Tasks::query();
        
        $data->with('dailyTask','project');
        
        $data->with(['taskdatestiming' => function ($query) use ($request) {
            $ddd = $request->start_date ?  date("Y-m-d",strtotime($request->start_date)) : date('Y-m-d');
            $query->whereDate('date',$ddd)->with('tasktiming');
        }])->first();
        
        $data = $data->whereIn('id',$mergedCollection)->orderBy('id','desc')->paginate('1000');
        
        $projectsTasksCount = [];
        
        $projectsTasksCount[0] = [
            'name' => "All",
            'task_count' => $data->count(),
        ];
    
        if(count($data) > 0){
              foreach($data as $d){
                   
                    $projectId = $d->project->id;
                    $projectName = $d->project->name;
                    
                    if (!isset($projectsTasksCount[$projectId])) {
                        $projectsTasksCount[$projectId] = [
                            'name' => $this->trimToNCharacters($projectName,10),
                            'task_count' => 1,
                            'project_id' => $projectId
                        ];
                    }else{
                        $projectsTasksCount[$projectId]['task_count']++;
                    }
                
              }
        }
        if(count($data) > 0){
               foreach($data as $d){
                   if($d->type == 1){
                       $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id','0')->whereDate('created_at',date('Y-m-d'))->first();
                   }else{
                       $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id',$d->taskdatestiming->id)->first();
                   }
                   $d['taskDate'] = $taskTiming ? $taskTiming->toArray() : null;
               }
        }
        
        return view('admin.tasks.tabledata',compact('data','projectsTasksCount'));
    }
    
    public function AjaxUserAssignTasks(Request $request){
        $date = $request->start_date ?? date("Y-m-d");
        $dailyTask = $this->dailyTasks($request);
        $weeklyTask = $this->weeklyTasks($request);
        $mergedCollection = array_merge($dailyTask,$weeklyTask);
        $data = $this->mergeTasks($mergedCollection,$request);
        
        $project = $request['project'] ?? '';
        
        $projectData = Projects::find($project);
        $dailyReport = DailyReport::where('project_id',$project)->where('user_id',auth()->user()->id)->get(['date','complation_rate','user_id']);
        $request['project'] = "";

        $dailyTaskC = $this->dailyTasks($request);
        $weeklyTaskC = $this->weeklyTasks($request);
        $mergedCollectionC = array_merge($dailyTaskC,$weeklyTaskC);
        $dataC = $this->mergeTasks($mergedCollectionC,$request);
        
        $projectsTasksCount = [];
        
        $projectsTasksCount[0] = [
            'name' => "All",
            'task_count' => $dataC->count(),
        ];
    
        if(count($dataC) > 0){
            foreach($dataC as $d){
                $projectId = $d->project->id;
                $projectName = $d->project->name;
                
                if (!isset($projectsTasksCount[$projectId])) {
                    $projectsTasksCount[$projectId] = [
                        'name' => $this->trimToNCharacters($projectName,10),
                        'task_count' => 1,
                        'project_id' => $projectId
                    ];
                }else{
                    $projectsTasksCount[$projectId]['task_count']++;
                }
            }
        }
        
        $dataa = $data;
        
        $request['totalTask'] = $dataa->count();
        $request['pendingTask'] = $dataa->where('report',NULL)->count();
        $request['doneTask'] = $dataa->where('report','!=',NULL)->count();
        
        if(count($data) > 0){
              $userId = $request->userId ?? auth()->user()->id;
               foreach($data as $d){
                   if($d->type == 1){
                       $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id','0')->where('user_id',$userId)->whereDate('start_date',$date)->first();
                   }else{
                       $taskTiming = TaskTiming::where('task_id',$d->id)->where('task_date_id',$d->taskdatestiming->id)
                       ->where('user_id',$userId)->whereDate('start_date',$date)->orderBy('id','desc')->first();
                   }
                   $d['taskDate'] = $taskTiming ? $taskTiming->toArray() : null;
               }
        }
        return response()->json([
            'data' => $data,
            'projectsTasksCount' => $projectsTasksCount,
            'project' => $project,
            'projectData' => $projectData,
            'date' => $date,
            'dailyReport' => $dailyReport
        ]);
    }
    
    public function dailyTasks($request)
    {
        $date = $this->getFormattedDate($request->start_date);
    
        $dailyTask = Tasks::with('users')
                          ->where('completion', '!=', 1)
                          ->where('type', '1')  // Daily tasks only
                          ->whereDate('created_at', '<=', $date);
    
        $this->applyProjectFilter($dailyTask, $request);
        $this->applyReportStatusFilter($dailyTask, $request, $date);
    
        $userId = $request->userId ?? auth()->user()->id;
    
        return $dailyTask->whereHas('users', function ($query) use ($userId) {
                            $query->where('user_task.user_id', $userId)
                                  ->where('user_task.status', 1)
                                  ->whereNull('user_task.deleted_at');
                         })
                         ->pluck('id')
                         ->toArray();
    }
    
    public function weeklyTasks($request)
    {
        $date = $this->getFormattedDate($request->start_date);
    
        $weeklyTask = Tasks::query()
                           ->where('completion', '!=', 1)
                           ->where('type', '!=', '1')  // Exclude daily tasks
                           ->whereHas('users', fn($query) => $query->where('users.id', $request->userId ?? auth()->user()->id));
    
        $this->applyProjectFilter($weeklyTask, $request);
        
        $weeklyTask->whereHas('taskdates', function ($query) use ($date, $request) {
            if ($request->status == "4") {
                $query->whereDate('date', $date)->whereHas('report');
            } elseif ($request->status == "0") {
                $query->whereDate('date', $date)->doesntHave('report');
            } else {
                $query->whereDate('date', $date);
            }
        });
    
        return $weeklyTask->pluck('id')->toArray();
    }
    
    private function getFormattedDate($date)
    {
        return $date ?? date("Y-m-d");
    }
    
    private function applyProjectFilter(&$taskQuery, $request)
    {
        if ($request->project) {
            $taskQuery->whereHas('project', function ($query) use ($request) {
                $query->where('id', $request->project);
            });
        }
    }
    
    private function applyReportStatusFilter(&$taskQuery, $request, $date)
    {
        if ($request->status == "4") {
            $taskQuery->whereHas('report', fn($query) => $query->whereDate('created_at', $date));
        } elseif ($request->status == "0") {
            $taskQuery->whereDoesntHave('report', fn($query) => $query->whereDate('created_at', $date));
        }
    }
    
    
    public function mergeTasks($mergedCollection,$request){
        $date = $request->start_date ?? date("Y-m-d");
        
        $data = Tasks::query();
        
        $data->with('tasktime','dailyTask','project','organiser:id,name,email');
        
        $userId = $request->userId ?? auth()->user()->id;
        
        $data->with(['report' => function ($query12) use ($date,$userId) {
                $query12->whereDate('submit_date',$date);
                $query12->where('user_id',$userId);
        }]);
        
        $data->with(['taskdatestiming' => function ($query) use ($request) {
            $ddd = $request->start_date ?  date("Y-m-d",strtotime($request->start_date)) : date('Y-m-d');
            $query->whereDate('date',$ddd)->with('tasktiming')->orderBy('id', 'desc');;
        }])->first();
        return  $data->whereIn('id',$mergedCollection)->orderBy('created_at','desc')->paginate('1000');
    }
    
    public function taskStatus($id,$status){

        $response  = Reports::find($id)->update(['status' => $status]);

        if($response){
            return  back()->with('message','Success! Tasks status changed successfully.');
        }

        return back()->with('error','Error! Please try Again After Sometime.');

    }
    
    public function status(Request $request,$id,$status){
        $response = Tasks::find($id)->update([
            'status' => $status,  
            'hold_remark' => $request->remark,
        ]);
        if ($request->has('user') && is_array($request->user)) {
            foreach ($request->user as $userId) {
                $taskUser = TaskUser::where('user_id', $userId)->where('task_id', $id)->first();
                if ($taskUser) {
                    $taskUser->update([
                        'status' => $status ,
                    ]);
                }
            }
        }
        if($response && $status == 1){
            return  back()->with('message','Success! Tasks Resumed successfully.');
        }elseif($response && $status == 0){
            return  back()->with('message','Success! Tasks Paused successfully.');
        }else{
           return  back()->with('message','Success! Tasks status changed successfully.'); 
        }
        return back()->with('error','Error! Please try Again After Sometime.');
    }

    private function startdateReport(Request $request){
        // dd($request->all());
        $date = Carbon::now(); 
        $message = $request->message;
        $date = isset($request->date) ?  date("Y-m-d",strtotime($request->date)) : $date->format('Y-m-d');
        $check = TaskTiming::where('user_id',auth()->user()->id)
                ->where('task_id',$request->task_id)
                ->where('task_date_id',$request->dateId)
                ->whereDate('start_date',$date)
                ->first();
        $date = $date." ".date('H:i:s');
        if($message == 'pausedTask'){
            $data = $check->update([
                'paused_time' => $date,
            ]);
        }elseif($message == 'resumeTask'){
            $data = $check->update([
                'restart_time' => $date,
            ]);
        }elseif($message == 'endTask'){
            $data = $check->update([
                'end_date' => $date,
            ]);
        }else{
            $data = TaskTiming::create([
                'start_date' =>  $date,
                'task_date_id' => $request->dateId,
                'end_date' => $request->end_date,
                'task_id' => $request->task_id,
                'user_id' => auth()->user()->id
            ]);
        }
        if($data){
            return $this->success('created','Start Date ','');
        }
        return $this->success('error','Error!');
    }
    
    public function sendGenerateReport(Request $request,$projectiId){
        $date = date("Y-m-d",strtotime($request->date));
        $request['project'] = $projectiId;
        
        $totalTask = $request->totalTask;
        $completeTask = $request->completeTask;
        $pendingTask = $request->PendingTask;

        $dailyTaskStates = DB::table('daily_task_states')->where('date',$date)->count();
        if($dailyTaskStates >=1){
            DB::table('daily_task_states')
            ->where('date', $date)
            ->update([
                'total_task' => $totalTask,
                'pending_task' => $pendingTask,
                'complate_task' => $completeTask,
            ]);
        }else{
            DB::table('daily_task_states')->insert([
                'date' => $date,
                'user_id' => auth()->user()->id,
                'total_task' => $totalTask,
                'pending_task' => $pendingTask,
                'complate_task' => $completeTask,
            ]);
        }
        $complationRate =  ($completeTask / $totalTask ) *100;
        $dailyReport = new DailyReport();
        $dailyReport->user_id = auth()->user()->id;
        $dailyReport->date = Carbon::today();
        $dailyReport->complation_rate = round($complationRate,2);
        $dailyReport->project_id = $projectiId;
        $dailyReport->save();
        
        $project = Projects::with('projectManager:id,name,email','teamLeader:id,name,email','client:id,name,email','users')->find($projectiId);
        $projectManager = $project->users()->whereHas('roles', function ($query) {
            $query->where('name', 'Project-Manager');
        })->first();
        
        if ($projectManager) {
            $cc = $to = "suyalvikas@gmail.com," . auth()->user()->email . ",".$projectManager->email." ,shvngupta21@gmail.com";
        } else {
            echo 'No project manager found.';
        }
        $user = auth()->user()->email;
        $admin = User::where('role_id','1')->first();
    
        $dailyTask = $this->dailyTasks($request);
        $weeklyTask = $this->weeklyTasks($request);
        $mergedCollection = array_merge($dailyTask,$weeklyTask);
        $data = $this->mergeTasks($mergedCollection,$request);
        
        $date  = date("M d, Y",strtotime($request->date));
        
        
        try{
        // $to = 'hr@adxventure.com'; //'nikhill@mailinator.com'; //$project->email;
        $subject = auth()->user()->name .' - Work Report ('. $date .') - Project : '.$project->name;
        
        // $cc = $to = $admin->email.",".auth()->user()->email.",".
        // $project->projectManager->email.",".$project->teamLeader->email.",".$project->client->email.",nikhill@mailinator.com";

        $contentDynamic = $request->remark ?? "";
        $html = view('admin.email.ReportEmail',compact('data','date','subject','project','contentDynamic'));
        // $html = view('admin.email.ReportEmail',compact('data','date','subject','project'));

        $fromName  = 'TMS - Adxventure';
        $fromEmail = 'info@adxventure.com';

        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $fromName <$fromEmail>\r\n";
        $headers .= "Reply-To: $fromEmail\r\n";
     
    
        // Send email
        Log::info("CC Sent Successfully", ['user' => $user]);
        $mailSent = mail($to, $subject, $html, $headers);

        }catch (Exception $e) {
            Log::info("error  ",$e->getMessage());
            return $e->getMessage();
        }
        
                    // Check if the email was sent successfully
        if ($mailSent) {
            $message = 'Success ! Report Email sent successfully.';
        } else {
            $message = 'Error ! Please try again after sometime.';
        }

        if($message){
            return $this->success('success',$message ,'');
        }

        return $this->error('error','Error! Please try again after sometime.');

    }
    
    public function getProjectDetails($id = 0){
        $data = Projects::with('projectManager','teamLeader','client')->find($id);

        if($data){
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }
        return false;
    }
    
    public function getTaskDetails(Request $request){
        
        $id = $request->id;
        
        $data = Tasks::find($id);

        if($data){
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }
        return false;
    }


    public function taskComplete(Request $request){
        $validated = $request->validate([
            'task_id' => 'required|numeric',
            'remark' => 'required|string|max:500',
        ]);

     

        //get the projet manager
        // $projectManagers = ProjectUser::where('project_id', $task->project_id)
        // ->whereHas('users', function ($query) {
        //     $query->whereHas('roles', function ($roleQuery) {
        //         $roleQuery->where('name', 'Project-Manager');
        //     });
        // })
        // ->first();
        // dd($projectManagers->users->email);

 
        // Email details
        // $to = 'manjeetchand01@gmail.com';
        // $subject = 'Task Complete Approvel';
        // $name = strtoupper($projectManagers->users->name); 
        // $message = 'Dear <strong>' . $name . '</strong>,<br><br>' .
        //         'Please find attached the invoice for your recent work.<br><br>' .
        //         'Thank you for your business.';
  

        // // Headers
        // $boundary = md5(uniqid(time()));
        // $headers = "MIME-Version: 1.0\r\n";
        // $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
        // $headers .= "From: info@adxventure.com\r\n";

        // // Email Body
        // $body = "--{$boundary}\r\n";
        // $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        // $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        // $body .= $message . "\r\n";

        // $body .= "--{$boundary}--";

        // // Send email
        // mail($to, $subject, $body, $headers);

        //get the tasks 
        $data = Tasks::find($request->task_id);
        $project = Projects::with('projectManager:id,name,email','teamLeader:id,name,email','client:id,name,email','users')->find($data->project_id);
        $projectManager = $project->users()->whereHas('roles', function ($query) {
            $query->where('name', 'Project-Manager');
        })->first();

        $subject = auth()->user()->name .' - Task Completation Report - Project : '.$project->name;
        
        if ($projectManager) {
            $cc = $to = "suyalvikas@gmail.com," . auth()->user()->email . ",".$projectManager->email." ,shvngupta21@gmail.com";
        } else {
            echo 'No project manager found.';
        }

        $contentDynamic = $request->remark ?? "";
        $html = view('admin.email.taskComplate',compact('data','subject','project','contentDynamic'));
        // $html = view('admin.email.ReportEmail',compact('data','date','subject','project'));

        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: info@adxventure.com";

        // Send email
        $mailSent = mail($to, $subject, $html, $headers);
        if($data){
            $data->completion = 1;
            $data->complete_remark = $request->reamrk;
            $data->save();
            return back()->with('message','Task Complete');
        }else{
            
            abort(503);
        }
    }
}
