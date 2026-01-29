<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Reports, Tasks, Medias, User, Projects, Roles,LateReason};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PDF;
require_once app_path('Helpers/helpers.php'); // ✅ Include helper here

class ReportController extends Controller
{
    protected $taskController;

    public function __construct(TaskController $taskController)
    {
        $this->taskController = $taskController;
    }

    public function index(request $request, $id = "")
    {
        // $data = Projects::where('team_leader',auth()->user()->id)->pluck('id')->toArray();
        $data = User::query();

        $data->whereIn('role_id', [6, 7]);
        $data->whereHas('tasks', function ($qq) {
            $qq->where('team_leader_id', auth()->user()->id);
        });

        $data =  $data->with('role', 'tasks')->get();
        return  view('admin.reports.index', compact('data'));
    }

    public function taskReport(Request $request)
    {
        // Fetch departments and projects
        $departments = Roles::whereNotIn('id', [1, 5])->withCount('user')->orderBy('name', 'asc')->get();
        $projects = Projects::orderBy('name', 'asc')->get();
    
        // Begin building the User query
        $data = User::query();
        
        // Eager load 'LateReason' with today's data
        $data = User::with(['LateReason' => function ($query) {
        $query->whereDate('created_at', Carbon::today());
        }])->whereNotIn('role_id', [1, 5]); // apply your other conditions
    
    
        // Filter by department if provided
        if ($request->department) {
            $data->where('role_id', $request->department);
        }
    
        // Get the authenticated user's role
        $userRole = auth()->user()->role_id;
    
        // Begin building the Projects query
        $project = Projects::query();
    
        // Filter by project if provided
        if ($request->project) {
            $project->where('id', $request->project);
        }
    
        // Apply role-based project filtering
        if ($userRole == 3) { // Project Manager
            $project->with('users')->where('manager', auth()->user()->id);
        } elseif ($userRole == 4) { // Team Leader
            $project->with('users')->where('team_leader', auth()->user()->id);
        } else { // Other roles
            $project->with('users');
        }
    
        // Get the list of user IDs related to the selected project
        $users = $project->first()->users->pluck('id')->toArray() ?? [];
    
        // Apply additional user filtering based on role
        if (in_array($userRole, [3, 4])) {
            $data->whereIn('id', $users);
        }
    
        // Ensure the user is active
        $data->where('is_active', '1');
    
        // Filter by project for Admins if applicable
        if ($userRole == 1 && $request->project) {
            $data->whereIn('id', $users);
        }
    
        // Eager load tasks
        $data->with('tasks');
    
        // Sort and retrieve the data
        $data = $data->orderBy('id', 'desc')->get();
    
        // Processing tasks and completion status if data exists
        if (count($data) > 0) {
            if ($request->date) {
                $request['start_date'] = date("Y-m-d", strtotime($request->date));
            }
    
            foreach ($data as $user) {
                $request->userId = $user->id;
    
                // Fetch daily and weekly tasks
                $dailyTask = $this->dailyTasksList($request, $user->id);
                $weeklyTask = $this->weeklyTasksList($request, $user->id);
    
                // Merge task collections
                $mergedCollection = array_merge($dailyTask, $weeklyTask);
                $taskData = $this->mergeTasksList($mergedCollection, $request);
    
                // Calculate task completion statistics
                $totalAssignedTasks = $taskData->count();
                $totalCompletedTasks = $taskData->where('report', '!=', NULL)->count();
    
                $user->totalAssignedTasks = $totalAssignedTasks;
                $user->totalCompletedTasks = $totalCompletedTasks;
    
                // Default task completion status color
                $user->color = "primary";
                if ($totalAssignedTasks == "0") {
                    $taskCompletionStatus = "No Work Assigned";
                } elseif ($totalCompletedTasks == "0") {
                    $user->color = "danger";
                    $taskCompletionStatus = "0% Work Done";
                } elseif ($totalAssignedTasks == $totalCompletedTasks) {
                    $user->color = "success";
                    $taskCompletionStatus = "100% Work Done";
                } else {
                    // Calculate completion percentage
                    $completionPercentage = ($totalCompletedTasks / $totalAssignedTasks) * 100;
    
                    if ($completionPercentage <= 70) {
                        $user->color = "danger"; // Red
                    } elseif ($completionPercentage > 70 && $completionPercentage < 100) {
                        $user->color = "warning"; // Orange
                    } else {
                        $user->color = "success"; // Green
                    }
    
                    $taskCompletionStatus = round($completionPercentage, 2) . "% Work Done";
                }
    
                $user->taskCompletionStatus = $taskCompletionStatus ?? 0;
            }
        }
        // Return the view with the data
        return view('admin.reports.index', compact('data', 'projects', 'departments'));
    }


    public function taskReportTest(Request $request){
        $departments = Roles::whereNotIn('id', [1, 5])->withCount('user')->orderBy('name', 'asc')->get();
        $projects = Projects::orderBy('name', 'asc')->get();
        $data = User::where('is_active',1)->whereNotIn('role_id', [1, 5])->get();
        if (count($data) > 0) {
            if ($request->date) {
                $request['start_date'] = date("Y-m-d", strtotime($request->date));
            }
    
            foreach ($data as $user) {
                $request->userId = $user->id;
    
                // Fetch daily and weekly tasks
                $dailyTask = $this->dailyTasksList($request, $user->id);
                $weeklyTask = $this->weeklyTasksList($request, $user->id);
    
                // Merge task collections
                $mergedCollection = array_merge($dailyTask, $weeklyTask);
                $taskData = $this->mergeTasksList($mergedCollection, $request);
    
                // Calculate task completion statistics
                $totalAssignedTasks = $taskData->count();
                $totalCompletedTasks = $taskData->where('report', '!=', NULL)->count();
    
                $user->totalAssignedTasks = $totalAssignedTasks;
                $user->totalCompletedTasks = $totalCompletedTasks;
    
                // Default task completion status color
                $user->color = "primary";
                if ($totalAssignedTasks == "0") {
                    $taskCompletionStatus = "No Work Assigned";
                } elseif ($totalCompletedTasks == "0") {
                    $user->color = "danger";
                    $taskCompletionStatus = "0% Work Done";
                } elseif ($totalAssignedTasks == $totalCompletedTasks) {
                    $user->color = "success";
                    $taskCompletionStatus = "100% Work Done";
                } else {
                    // Calculate completion percentage
                    $completionPercentage = ($totalCompletedTasks / $totalAssignedTasks) * 100;
    
                    if ($completionPercentage <= 70) {
                        $user->color = "danger"; // Red
                    } elseif ($completionPercentage > 70 && $completionPercentage < 100) {
                        $user->color = "warning"; // Orange
                    } else {
                        $user->color = "success"; // Green
                    }
    
                    $taskCompletionStatus = round($completionPercentage, 2) . "% Work Done";
                }
    
                $user->taskCompletionStatus = $taskCompletionStatus ?? 0;
            }
        }
        return view('admin.reports.index-test',compact('data', 'projects', 'departments'));
    }
    

    public function taskProjectReport(Request $request, $id)
    {
        $request->project = $id;
        return $this->taskReport($request);
    }

    public function create($id)
    {
        $data = Tasks::find($id);
        return  view('admin.report.create', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Find the task by ID or fail
        $data = Tasks::with('tasktime')->findOrFail($request->id);

       // Assuming $data is your object containing the task time data
        $startDate = Carbon::parse($data->tasktime->start_date);
        $endDate = Carbon::parse($data->tasktime->end_date);
        $pausedTime = Carbon::parse($data->tasktime->paused_time);
        $restartTime = Carbon::parse($data->tasktime->restart_time);

        // Total duration between start and end
        $totalDuration = $endDate->diffInMinutes($startDate);

        // Duration during which the task was paused
        $pauseDuration = $restartTime->diffInMinutes($pausedTime);

        // Final time excluding the pause time
        $overallTimeInMinutes = $totalDuration - $pauseDuration;

        $data->task_timing = $overallTimeInMinutes;
        $data->save();

        $date = Carbon::now(); 
        $data->tasktime->update([
            'end_date' => $date,
        ]);
        // Initialize the validator
        $validatorRules = [
            'task_status' => 'required',
        ];
    
        // Conditional validation rules based on task attributes
        if ($request->attachment) {
            $validatorRules['attachment'] = 'required';
            $validatorRules['attachment.*'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }
    
        if ($data->remark_needed == 1) {
            $validatorRules['remark'] = 'required|max:5000';
        }
    
        if ($data->url == 1) {
            $validatorRules['url'] = 'required|url';
        }
    
        // Validate the request
        $validator = Validator::make($request->all(), $validatorRules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        // Handle file uploads
        $mediaData = [];
        if ($request->hasFile('attachment')) {
            foreach ($request->attachment as $attach) {
                $destinationPath = 'images'; // Set your desired destination path
                $imageName = time() . rand(1, 9999) . '.' . $attach->getClientOriginalExtension();
                $attach->move($destinationPath, $imageName);
                $mediaData[] = ['filename' => $imageName];
            }
        }
    
        // Prepare data for the report creation
        $dataa = $request->all();
        $dataa['task_id'] = $data->id;
        $dataa['user_id'] = auth()->user()->id;
        $dataa['submit_date'] = date('Y-m-d', strtotime($request->submit_date));
    
        // Create the report and attach media
        $response = Reports::create($dataa);
        $response->media()->createMany($mediaData); 

        $dailyTask = $this->taskController->dailyTasks($request);
        $weeklyTask = $this->taskController->weeklyTasks($request);
        $mergedCollection = array_merge($dailyTask,$weeklyTask);
        $data = $this->taskController->mergeTasks($mergedCollection,$request);
        $taskcount['totalTask'] = $data->count();
        $taskcount['pendingTask'] = $data->where('report',NULL)->count();
        $taskcount['doneTask'] = $data->where('report','!=',NULL)->count();
        // Prepare response data
        $responseData = [
            'status' => 'created',
            'message' => 'Report Submitted Successfully.',
            'data' => $taskcount
        ];

        if ($response) {
            return response()->json($responseData);
        }
    
        return $this->success('error', 'Report submission failed.');
    }
    

    public function userAttachments($userId, $id)
    {

        $response = Reports::with('media', 'task')
            ->where('user_id', $userId)
            ->where('task_id', $id)
            ->orderBy('id', 'desc')->first();
        $data = $response->media ?? [];

        return  view('admin.report.view', compact('data', 'response'));
    }

    public function attachments($id)
    {
        $response = Reports::with('media', 'task')
            ->where('task_id', $id)->orderBy('id', 'desc')->first();
        $data = $response->media ?? [];
        return  view('admin.report.view', compact('data', 'response'));
    }

    public function destroy($id)
    {
        $response = Reports::find($id)->delete();
        if ($response) {
            return  back()->with('message', 'Success! Report deleted Successfully.');
        }
        return back()->with('message', 'Error! Please try Again After Sometime.');
    }

    public function generateReport(Request $request, $projectiId)
    {
        $data = Tasks::where('project_id', $projectiId)->where('assign', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->get();
        return view('admin.reports.generate', compact('data', 'projectiId'));
    }

    public function sendGenerateReport($projectiId)
    {

        $data = Tasks::with('project')->where('project_id', $projectiId)
            ->where('assign', auth()->user()->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->get();

        $to = $data[0]->project->email;
        $subject = auth()->user()->name . ' - Wrok Report (' . date('Y-m-d') . ') - ' . $data[0]->project->name;
        $cc = auth()->user()->email;

        // Build the raw HTML content
        $html = '<html><head><title>' . $subject . '</title></head><body>';
        $html .= '<h2>' . $subject . '</h2>';
        $html .= '<p>Task List :</p>';
        $html .= '<table border="1">';
        $html .= '<tr><th>S.No</th><th>Task Name</th><th>Assign Date</th><th>Priority</th><th>Status</th></tr>';

        if (count($data) > 0) {
            $counter = 1;
            foreach ($data as $dd) {
                $html .= '<tr>';
                $html .= '<td>' . $counter++ . '</td>';
                $html .= '<td>' . $dd->name . '</td>';
                $html .= '<td>' . date("d M, Y", strtotime($dd->created_at)) . '</td>';
                $html .= '<td>' . $this->status($dd->category) . '</td>';
                if ($dd->status == 4)
                    $html .= '<td>Done</td>';
                else {
                    $html .= '<td>Pending</td>';
                }
                $html .= '</tr>';
            }
        }

        $html .= '</table>';
        $html .= '</body></html>';

        // Sender info
        $fromName  = 'TMS - Adxventure';
        $fromEmail = 'info@adxventure.com';

        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $fromName <$fromEmail>\r\n";
        $headers .= "Reply-To: $fromEmail\r\n";
        $headers .= "Cc: $cc\r\n";

        // Send email
        $mailSent = mail($to, $subject, $html, $headers);

        // Check if the email was sent successfully
        if ($mailSent) {
            return back()->with('success', 'Success ! Report Email sent successfully.');
        } else {
            return back()->with('error', 'Error ! Please try again after sometime.');
        }
    }


    public function status($status)
    {
        if ($status == 1) {
            return "NORMAL";
        } elseif ($status == 2) {
            return  "MEDIUM";
        } elseif ($status == 3) {
            return  "HIGH";
        } elseif ($status == 4) {
            return  "URGENT";
        }
    }


    public function Reject(Request $request,$id,$status){
        $report  =Reports::findorfail($id);
        $report->status = $status;
        $report->reject_remark = $request->remark;
        $report->save();
        return back()->with('success', 'Report Rejected Successfully');
    }

   public function late_report()
    {
        $team = auth()->user()->teamMembers;

        if ($team && $team->count() > 0) {
            $teamIds = $team->pluck('id')->toArray();

            $data = LateReason::whereDate('created_at', Carbon::today())
                    ->where(function ($query) use ($teamIds) {
                        $query->whereIn('user_id', $teamIds)
                            ->orWhere('user_id', auth()->user()->id);
                    })
                    ->orderBy('login_time', 'asc')
                    ->get();
        } else {
            $data = LateReason::whereDate('created_at', Carbon::today())->orderBy('login_time', 'asc')->get();
        }

        return view('admin.user.late-report', compact('data'));
    }

    
    public function user_late_report($id)
    {
        $startDate = Carbon::now()->startOfMonth()->toDateString(); 
        $endDate   = Carbon::now()->endOfMonth()->toDateString();

        $data = LateReason::with('user')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $user = User::findorFail($id);
        // $projectManager = $user->whereHas('roles', function ($query) {
        //     $query->where('name', 'Project-Manager');
        // })->first();

        $count['this_month_late'] = LateReason::with('user')
            ->where('user_id', $id)
            ->whereNotNull('status')
            ->whereBetween('created_at', [$startDate, $endDate])  
            ->count();

        $count['total_late'] = LateReason::with('user')
            ->where('user_id', $id)
            ->whereNotNull('status')
            ->count();
        $count['this_month'] = LateReason::with('user')
            ->where('user_id', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $count['total'] = LateReason::with('user')
            ->where('user_id', $id)
            ->count();

        return view('admin.user.user-late-report', compact('data', 'count','user'));
    }

    public function otherReport(Request $request){
        $data = $request->all();
        $subject = "Today Report – " . auth()->user()->name . " – " . now()->format('d M Y, h:i A');
        $header = "Today Report";
        $footer = " <p>Best Regards,<br>" . auth()->user()->name . "<br>" . auth()->user()->roles()->first()->name . "</p>";

        // =============================
        // 🧾 Create Task Table
        // =============================

        $tasks = "";
        if (!empty($data['task_name']) && !empty($data['task_timing'])) {
            $tasks .= '<table border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="text-align:left;">#</th>
                        <th style="text-align:left;">Task Name</th>
                        <th style="text-align:left;">Time (Minutes)</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($data['task_name'] as $index => $taskName) {
                $taskTime = $data['task_timing'][$index] ?? '';
                $tasks .= "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>" . e($taskName) . "</td>
                    <td>" . e($taskTime) . "</td>
                </tr>";
            }

            $tasks .= '</tbody></table>';
        }

        // =============================
        // 📩 Email Body
        // =============================
        $message = "
            <p>Dear Team,</p>
            <p>Please find below today's task report from <strong>" . auth()->user()->name . " (" . auth()->user()->roles()->first()->name . " )</strong>:</p>
            {$tasks}
        ";
        // HR recipients

        $to = [
            'suyalvikas@gmail.com',
            auth()->user()->email,
            'priyanka@adxventure.com',
        ];
       
        $recipients = implode(',', $to);
      
        // Send mail to HR
        sendMail($recipients,$subject, $header, $footer,$message);
        return back()->with('success','Report send successfully.');
    }
}