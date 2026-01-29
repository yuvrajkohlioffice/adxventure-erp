<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Projects,Tasks,Category,ProjectCategory,Work,Invoice,Payment,Department,lead,CustomRole,client,ProjectUser,TaskUser,Proposal,Credential,Roles,ProjectInvoice};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Hash;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasRole(['Super-Admin', 'Admin'])) {
            $data = Projects::with('users');
        } else {
            $data = Projects::whereHas('users', function ($query) {
                $query->where('project_user.user_id', Auth::id());
            });
        }

        // Apply filters based on the request inputs
        if ($request->name) {
            $data->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->client) {
            $data->where('client_id', $request->client);
        }
        if ($request->projectstatus) {
            if ($request->projectstatus == 'hold') {
                $data->where('status', 0);
            } else {
                $data->where('status', $request->projectstatus);
            }
        }

        // Fetch users with specific roles
        $users = User::where('is_active',1)->whereHas('roles', function ($query) {
            $query->whereIn('name', ['Manager', 'Technology Executive','Digital Marketing Executive','Digital Marketing Intern','Project-Manager','Digital Marketing Manager']);
        })->get();

        // Apply additional relationships and pagination
        $data = $data->with('client', 'Followup')->orderBy('name', 'asc')->paginate(20);

        $tasks = Tasks::whereIn('project_id', $data->pluck('id'))->get();
        $roles = Roles::orderBy('name','asc')->whereNotIn('id',[1,5,2])->get();
        $users = USer::where('is_active',1)->whereNotIn('id',[1])->orderBy('name','asc')->get();
        return view('admin.projects.index', compact('data', 'users','tasks','roles','users'));
    }

    
    public function saveAndFinish(Request $request){
        return redirect()->route('projects.index')->with('message', 'Project Add successfully!');
    }
    public function show(Request $request){
        return redirect()->route('projects.index')->with('message', 'Project Add successfully!');
    }


    public function Project_details($project_id) {
        // Fetching project details with related models
        $project = Projects::with('client', 'Followup', 'users', 'invoice')->findorfail($project_id);
        // Getting users related to the project
        $projectUser = ProjectUser::with('users')->where('project_id', $project_id)->get();
        // Paginating tasks related to the project
        $tasks = Tasks::where('project_id', $project_id)->paginate(10);
        // Getting all tasks for the project (without pagination)
        $task = Tasks::where('project_id', $project_id)->get();
        // Fetching invoices related to the project's client
        $invoice = ProjectInvoice::where('client_id', $project->client->id)->get();
        // Fetching proposals based on the lead IDs from the invoices
        $proposals = Proposal::whereIn('invoice_id', $invoice->pluck('id'))->get();
        // Passing data to the view
        $credentials = Credential::where('project_id',$project_id)->paginate(10);
        $roles = Roles::orderBy('name','asc')->whereNotIn('id',[1,5,2])->get();
        return view('admin.projects.project_details', compact('project', 'projectUser', 'tasks', 'task', 'proposals','credentials','roles'));
    }
    

    public function create(Request $request,$invoiceId){

        $projectManagers = User::whereHas('roles', function($query) {
            $query->where('name','project-manager');
        })->get();
        $invoice =  ProjectInvoice::with(['client', 'project', 'payment', 'Followup','proposal','service','services','Office','lead'])->findorfail($invoiceId);
        return  view('admin.projects.create',compact('projectManagers','invoice'));  
    }

    public function store(Request $request) {
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|numeric',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|unique:projects',
            'contact_person_name' => 'required',
            'contact_person_mobile' => 'required|numeric',
            'project_manager' => 'required|numeric',
            'description' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        // Check if there's an existing project ID
        $invoice = projectInvoice::with(['client','lead'])->findorfail($request->invoice_id);
        $id = $invoice->client_id;
        if ($id) {
            $lead = User::find($id);
        } else {
            $lead = Lead::find($invoice->lead_id);
        }
    
        if (!$lead) {
            return abort(404, 'Lead or Client not found');
        }
    
        if (!$id) {
            // Create new user
            $user = new User();
            $user->name = $lead->name;
            // Explode the string by the hyphen '-'
            $phoneParts = explode('-',  $lead->phone);
            $phoneCode = (int) $phoneParts[0]; // Convert phone code to integer
            $phoneNumber = (int) $phoneParts[1];
            $user->phone_code = $phoneCode;
            $user->phone_no = $phoneNumber;
            $user->phone_code = $phoneCode;
            $user->phone_no = $phoneNumber;
            $user->email = $lead->email;
            $user->user_id = auth()->user()->id;
            $user->city = $lead->city;
            $user->date_of_joining = Carbon::now();
            $user->password = Hash::make('123456');
            $user->role_id = 5;
            if ($user->save()) {
                try {
                    $role = CustomRole::findById(5);
                    $user->assignRole($role);
                } catch (\Exception $e) {
                    // Rollback user creation if role assignment fails
                    $user->delete();
                    return response()->json(['error' => 'Error assigning role: ' . $e->getMessage()]);
                }
    
                // Update the lead
                $lead->status = 1;
                $lead->client_id = $user->id;
                $lead->save();
    
                // Send email
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                $headers .= "From: tms@adxventure.com";
    
                $to ='manjeetchand01@gmail.com';
                $subject = 'Adxventure Client Add Email';
                $name = $lead->name;
                $loginUrl = asset('login');
    
                $html = '<html>
                    <head>
                        <title>' . $subject . '</title>
                    </head>
                    <body style="font-family: Arial, sans-serif;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9f9f9;">
                            <tr>
                                <td align="center">
                                    <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                                        <tr>
                                            <td style="background-color: #0d6efd; color: white; text-align: center; padding: 20px;">
                                                <h1 style="margin: 0;">Welcome to Adxventure</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #ffffff; color: #333; padding: 30px; text-align: left;">
                                                <p style="font-size: 18px; margin-bottom: 20px;">Dear ' . $name . ',</p>
                                                <p style="font-size: 16px;">Congratulations on becoming a part of Adxventure!</p>
                                                <p style="font-size: 16px;">We are thrilled to have you as our valued client, and we appreciate your trust in us.</p>
                                                <p style="font-size: 16px;">Our team is dedicated to providing you with an exceptional experience, and we look forward to assisting you in achieving your goals.</p>
                                                <p style="font-size: 16px;">To get started, please check your account by clicking on the following link:</p>
                                                <a href="' . $loginUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;">
                                                    Account Login Url
                                                </a>
                                                <h5>Login Credentials</h5>
                                                <p>Email: ' . $request->email . '</p>
                                                <p>Password: 123456</p>
                                                <p style="font-size: 16px;">If you have any questions or need assistance, feel free to reach out to our support team.</p>
                                                <p style="font-size: 16px;">Best regards,</p>
                                                <p style="font-size: 16px;">The Adxventure Team</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #0d6efd; color: white; text-align: center; padding: 10px;">
                                                <p style="font-size: 14px;">&copy; 2024 <a href="https://adxventure.com/" style="color: white; text-decoration: none;">Adxventure</a></p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>';
                mail($to, $subject, $html, $headers);
            } else {
                return abort(500, 'Client Not Added');
            }
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $destinationPath = 'projects/';
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $timestamp = now()->format('YmdHis'); // Current date and time
            $filename = $request->name . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $logo = $filename;
            try {
                $file->move($destinationPath, $filename);
            } catch (\Exception $e) {
                return response()->json(['error' => 'File could not be uploaded.'], 500);
            }
        } else {
            $logo = 'images.png';
        }
        
        // Prepare data for project creation
        $data = [
            'invoice_id' => $request->inovoice_id,
            'logo' => $logo,
            'jd' => $request->description,
            'user_id' => auth()->user()->id,
            'website' => $request->website,
            'client_id' => $id ? $id: $user->id,
            'name' => $request->name,
            'category' => $lead->client_category ?? $lead->category,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_mobile' => $request->contact_person_mobile,
            'social_media' => $request->social_media,
            'manager' => 0,
            'team_leader' => 0,
            'status' =>3,
        ];
    
        // Create project   
        $project = Projects::create($data);
    
        if ($project) {
            // // Update services
            // $services = Work::where('lead_id', $lead->id)->get();
            // foreach ($services as $service) {
            //     $service->project_id = $project->id;
            //     $service->client_id = $id ? $lead->id : $user->id;
            //     $service->save();
            // }
    
            // Attach project managers
            $project->users()->attach($request->project_manager, [
                'project_id' => $project->id,
                'assigned_user_id' => auth()->user()->id,
            ]);

            $invoice->is_project = 1;
            $invoice->save();
    
            // Return success response
            $url = $id ? url('project') : url('project');
            return $this->success('created', 'Project', $url);
        }
        return $this->success('error', 'Project');
    }
    
    
    public function edit($id){
        $data = Projects::with('users','bank','invoice')->find($id);
        $users = User::where('role_id',5)->where('status','1')->get();
        $exectives = User::with('role')->whereIn('role_id',[6,7,3,4])->where('id','!=',auth()->user()->id)->where('status','1')->get();
        $leader = User::where('role_id','4')->where('status','1')->get();
        $manager = User::where('role_id',3)->where('status','1')->get();
        $categories = Category::get();
        $projectCategories = ProjectCategory::get();
        $projectManagers = User::whereHas('roles', function($query) {
            $query->where('name', 'project-manager');
        })->get();
        $projectUser = ProjectUser::where('project_id',$id)->first();
        return  view('admin.projects.edit',compact('data','users','exectives','leader','manager','categories','projectCategories','projectManagers','projectUser'));
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required',
            'contact_person_name' => 'required',
            'contact_person_mobile' => 'required|numeric',
            'project_manager' => 'required|numeric',
            'description' => 'required',
            'category' => 'required',
            'project_category' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $destinationPath = 'projects/';
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $timestamp = now()->format('YmdHis'); // Current date and time
            $filename = $request->name . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $logo = $filename;
            try {
                $file->move($destinationPath, $filename);
            } catch (\Exception $e) {
                return response()->json(['error' => 'File could not be uploaded.'], 500);
            }
        } else {
            $logo = 'images.png';
        }
        $response = Projects::findorfail($id);
        $data = [
            'logo' => $logo,
            'jd' => $request->description,
            'user_id' => auth()->user()->id,
            'website' => $request->website,
            'name' => $request->name,
            'category' => $request->category,
            'project_category' => $request->project_category,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_mobile' => $request->contact_person_mobile,
            'social_media' => $request->social_media,
            'manager' => 0,
            'team_leader' => 0,
            'status' =>3,
        ];
        
        $response = $response->update($data);
        if($response){
            $url = url('project');
            return $this->success('updated','Project ',$url);
        }
        return $this->success('error','Project ');
    }

    public function destroy($id){
        $response = Projects::find($id)->delete();
        if($response){
            return  redirect()->route('users.index')->with('message','Success! projects deleted Successfully.');
        }
        return back()->with('error','Error! Please try Again After Sometime.');
    }

    public function UserAlAssignProjects(){
        if(auth()->user()->role_id == 1){
            $data = Projects::wherehas('task',function($query){
                 $query->where('status','4');
            })->withCount('task')->orderBy('id','desc')->get();
        }
        if(auth()->user()->role_id == 4 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 ){
            $projects = Tasks::select()->where('assign',auth()->user()->id)->distinct()->pluck('project_id')->toArray();
            $data = Projects::withCount('task')->whereIn('id',$projects)->orderBy('id','desc')->get();
        }
        return  view('admin.user.index',compact('data'));
    }
    
    
    public function taskProjects(Request $request){
        
        $data = Projects::query();
        $data->withCount('task');
        $data = $data->where('status','1')->paginate('25');
        
        return  view('admin.projects.taskReports',compact('data'));
        
    }
    
    
    public function status(Request $request,$id,$status){
        
        $data = Projects::find($id);
        
        if($status == "1"){
            $data->update(['status' => '1']);
             return back()->with('message','Project resumed successfully.');
        }elseif($status == "2"){
            $data->update(['status' => '2']);
            return back()->with('message','Project Completed successfully.');
        }else
        {
            if($request->hold_reason){
                if($request->hold_reason == "Other" && $request->reason){
                    $data->update(['status' => '0','reason'=>$request->reason]);
                }else{
                    $data->update(['status' => '0','reason'=>$request->hold_reason]);
                }
            }else{
                $data->update(['status' => '0']);
            }
            return back()->with('message','Project on-hold successfully.');
        }
        
    }

    public function createInvoice(Request $request, $client_id, $project_id) {
        $work = Work::where('client_id', $client_id)->where('project_id', $project_id)->get();
        $total_amount = $work->sum('work_price');
    
        if ($request->isMethod('get')) {
            $project = projects::with('work')->find($project_id);
            return view('admin.projects.invoice', compact('client_id', 'project_id', 'total_amount','project'));

        } else if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'invoice_date' => 'required',
                'invoice_type' => 'required',
                'bank_details' => 'required|numeric',
            ]);
    
            if ($request->advanced == 1) {
                $validator = Validator::make($request->all(), [
                    'mode' => 'required',
                    'receipt_number' => 'required',
                    'desopite_date' => 'required',
                    'amount' => 'required|numeric',
                    'remark' => 'required',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
            }
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $invoice = new Invoice();
            $invoice->client_id = $client_id;
            $invoice->project_id = $project_id;
            $invoice->in_date = $request->invoice_date;
            $invoice->time = Carbon::now()->format('H:i:s');
            $invoice->type = $request->invoice_type;
            $invoice->bank = $request->bank_details;
            $invoice->gst = 18;
            $invoice->invoice_amount = $total_amount;
            $invoice->discount = $request->discount;
            $invoice->save();
            $invoice_id = $invoice->id;
    
            // Update Work models
            Work::where('client_id', $client_id)->where('project_id', $project_id)->update(['invoice_id' => $invoice_id]);
    
            if ($request->advanced == 1) {
                $payment = new Payment();
                $payment->invoice_id = $invoice_id;
                $payment->mode = $request->mode;
                $payment->receipt_number = $request->receipt_number;
                $timePart = Carbon::now()->format('H:i:s');
                $datePart = date('Y-m-d', strtotime($request->desopite_date));
                $payment->desopite_date = $datePart . ' ' . $timePart;
                $payment->amount = $request->amount;
                $payment->pending_amount = $total_amount - $request->amount;
                $payment->remark = $request->remark;
                $payment->payment_status = "Advanced";
                if ($request->hasFile('image')) { 
                    $image = $request->file('image');
                    $currentYear = date('Y');
                    $currentMonth = date('m');
                    $storagePath = "images/{$currentYear}/{$currentMonth}/";
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path($storagePath), $fileName);
                    $payment->image = $storagePath . $fileName;
                }

                $payStatus = Invoice::find($invoice_id);
                $payStatus->pay_status = 1;
                $payStatus->save();
                $payment->save();
            }
            $url = url('/projects');
            return $this->success('updated','Invoice ',$url);
        }
    }
    

    public function work($client_id, $project_id)
    {
        $works = Work::where('client_id', $client_id)
                        ->where('project_id', $project_id)
                        ->get();
        $project = Projects::find($project_id);
        return view('admin.projects.work.create', compact('client_id', 'project_id', 'works','project'));
    }


    public function workStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'work_name' => 'required',
            'work_quality' => 'required|numeric',
            'work_price' => 'required|numeric',
            'work_type' => 'required',
            'client_id' => 'required',
            'project_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $total_amount = $request->work_price * $request->work_quality;
        $work = new Work();
        $work->work_name = $request->work_name;
        $work->work_quality = $request->work_quality;
        $work->work_price = $request->work_price;
        $work->work_type = $request->work_type;
        $work->client_id = $request->client_id;
        $work->project_id = $request->project_id;
        $work->total_amount = $total_amount;
        $work->save();
        return redirect()->back()->with('message', 'Work added successfully.');
    }
    public function workUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'work_name' => 'required',
            'work_quality' => 'required|numeric',
            'work_price' => 'required|numeric',
            'work_type' => 'required',
            'client_id' => 'required',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $work = Work::find($request->id);
        if (!$work) {
            return redirect()->back()->with('error', 'Work not found.');
        }
        $work->update([
            'work_name' => $request->work_name,
            'work_quality' => $request->work_quality,
            'work_price' => $request->work_price,
            'work_type' => $request->work_type,
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'invoice_id' => $request->invoice_id,
            'total_amount' => $request->work_price * $request->work_quality, // Recalculate total_amount if necessary
        ]);

        return redirect()->back()->with('message', 'Work updated successfully.');
    }


    public function workDelete($id){
        $work = Work::find($id);
        if (!$work) {
            return redirect()->back()->with('error', 'Work not found.');
        }
        $work->delete();
        return redirect()->back()->with('message', 'Work Delete successfully.');
    }  


    public function MyProject()
    {
        $data = auth()->user()->projects()->paginate(20);
        return view('admin.projects.myproject', compact('data'));
    }

    public function AssignProjects(Request $request) 
    {
        if ($request->isMethod('get')) {
            $data = Projects::with('client', 'Followup', 'users')
                ->where('status', 3)
                ->orderBy('name', 'asc')
                ->paginate(20);
    
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'Manager');
            })->get();
    
            return view('admin.projects.assign-projects', compact('data', 'users'));
        } else {
            try {
                $validator = Validator::make($request->all(), [
                    'project_id' => 'required',
                    'assignd_user.*' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
    
                $project = Projects::findOrFail($request->project_id);
                
                $alreadyAssigned = [];
    
                foreach ($request->assignd_user as $userId) {
                    if ($project->users()->where('project_user.user_id', $userId)->exists()) {
                        $alreadyAssigned[] = User::find($userId)->name;
                    } else {
                        $project->users()->attach($userId, [
                            'project_id' => $project->id,
                            'user_id' => $userId,
                            'assigned_user_id' => auth()->user()->id,
                        ]);
                    }
                }
                if (!empty($alreadyAssigned)) {
                    $message = 'The following Employee  were already assigned: ' . implode(', ', $alreadyAssigned);
                    return redirect()->back()->with('error', $message);
                }
    
                return redirect()->back()->with('success', 'Project managers assigned successfully.');
    
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }
    }



    public function credintoal(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'name.*' => 'required|string', // Ensure it's a string
            'url.*' => 'required|url', // Use url validation rule
            'username.*' => 'required|string', // Ensure it's a string
            'password.*' => 'required|string|min:6', 
            'role.*' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        foreach ($request->name as $index => $name) {
            Credential::create([
                'project_id' => $request->project_id,
                'name' => $name,
                'url' => $request->url[$index],
                'username' => $request->username[$index],
                'password' => $request->password[$index], // Encrypt the password
                'role_id' => $request->role[$index],
            ]);
        }
        $url = url('/project');
        return $this->success('updated','Invoice ',$url);
        // Your code to handle valid input goes here
    }
    
    public function credintoalEdit(Request $request){   
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'name' => 'required|string', // Ensure it's a string
            'url' => 'required|url', // Use url validation rule
            'username' => 'required|string', // Ensure it's a string
            'password' => 'required|string|min:6', // Ensure it's a string with a minimum length
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $credentials = Credential::findorfail($request->id);
        $data =[
            'name' => $request->name,
            'url' => $request->url,
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $request->role,
        ];
        $credentials->update($data);
        $url = url('/project-details/' . $request->project_id);
        return $this->success('updated','Invoice ',$url);
    }
    
    public function credintoalDelete($id) {
        $credentials = Credential::findOrFail($id);
        $credentials->delete();
        
        return response()->json(['message' => 'Credential deleted successfully.']);
    }


   
}
