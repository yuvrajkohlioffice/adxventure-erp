<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Department,Roles, User,CustomRole,Leaves,};
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Hash;
use Mail;
use PDF;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{

    public function user_login($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user); 
        return redirect('/dashboard');
    }

    public function index(Request $request){
        $departments = Department::orderBy('name','asc')->get();
        $roles = CustomRole::orderBy('name','asc')->get();
        $query = User::query();
        $query->whereNotIn('role_id',[1,5]);
        if($request->filled('name')){
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if($request->filled('department')){
            $query->where('department_id',$request->department);
        }

        if($request->filled('status')){
            $query->where('is_active',$request->status);
        }
        $data = $query->orderBy('name', 'asc')->paginate('20');
        return  view('admin.employee.index',compact('data','departments','roles'));
    }

    
    public function create(){
        if (auth()->user()) {
            $user = auth()->user();
            // Check if the user has 'super-admin' role
            if ($user->hasRole('Super-Admin')) {
                $designation = CustomRole::select('id', 'name')->get();
            } elseif ($user->hasRole('Admin')) {
                $designation = CustomRole::select('id', 'name')->where('name', '!=', 'Super-Admin')->where('name', '!=', 'Admin')->get();
            } else {
                $designation = CustomRole::select('id', 'name')->where('name', '!=', 'Super-Admin')->get();
            }
        }
        $department = Department::orderBy('id','desc')->get();
        return  view('admin.employee.create',compact('department','designation')); 
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_no' => 'required|unique:users|numeric|digits:10',
            'date_of_joining' => 'required|date',
            'skills' => 'required|string|max:255',
            'designation' => 'required|exists:roles,id',
            'department' =>'required|numeric|exists:department,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $data = $request->except('profile_image', 'designation');
        $data['user_id'] = auth()->user()->id;
        $data['role_id'] = $request->designation;
        $data['department_id'] = $request->department;
        $data['date_of_joining'] = date('Y-m-d', strtotime($request->date_of_joining));
        $data['status'] = 1;
    
        if ($request->hasFile('profile_image')) {
            $attach = $request->file('profile_image');
            $destinationPath = 'profile/';
            $data['image'] = time() . rand(1, 998587899) . '.' . $attach->getClientOriginalExtension();
            $attach->move($destinationPath, $data['image']);
        }
    
        // Create the user
        try {
            $user = User::create($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating user: ' . $e->getMessage()]);
        }
    
        // Assign the role using Spatie's method
        try {
            $role = CustomRole::findById($request->designation);
            $user->assignRole($role);
        } catch (\Exception $e) {
            // Rollback user creation if role assignment fails
            $user->delete();
            return response()->json(['error' => 'Error assigning role: ' . $e->getMessage()]);
        }
    
        $url = route('users.index');
        return response()->json(['success' => 'User created successfully', 'redirect_url' => $url]);
    }
    

    public function edit($id){

        if(auth()->user()->role_id == 1){
            $designation = Roles::whereNotIn('id',[1,5])->get();
        }elseif(auth()->user()->role_id == 2){
            $designation = Roles::whereNotIn('id',[1,2])->get();
        }elseif(auth()->user()->role_id == 3){
            $designation = Roles::whereNotIn('id',[1,2,3,5])->get();
        }else{
            $designation = Roles::whereIn('id',[3,4,5])->get();
        }
        
        $department = Department::orderBy('id','desc')->get();
        
        $data = User::find($id);    

        return  view('admin.employee.edit',compact('department','designation','data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone_no' => 'required|numeric|digits:10|unique:users,phone_no,' . $id,
            'date_of_joining' => 'required|date',
            'skills' => 'required',
            'designation' => 'required|exists:roles,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $user = User::findOrFail($id);
        $data = $request->all();
        $data['role_id'] = $request->designation;
        $data['date_of_joining'] = date('Y-m-d', strtotime($request->date_of_joining));
    
        if ($request->hasFile('profile_image')) {
            $attach = $request->file('profile_image');
            $destinationPath = 'profile/';
            $data['image'] = time() . rand(1, 998587899) . '.' . $attach->getClientOriginalExtension();
            $attach->move($destinationPath, $data['image']);
        }
    
        if (!$request->filled('password')) {
            unset($data['password']);
        }
    
        $user->update($data);
    
        $role = CustomRole::findById($data['role_id']);
        $user->syncRoles([$role]);
    
        if ($user) {
            $url = route('users.index');
            Session::flash('success', 'User updated successfully.');
            return $this->success('updated', 'User', $url);
        }
    
        return $this->success('error', 'User');
    }
    
    public function updateStatus($id, $status)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Error! User not found.');
        }
        $user->is_active = $status;
        if($user->save()){
            if ($user->role_id == 5) {  
                if ($status == 1) {
                    return redirect()->route('user.client.index')->with('message', 'Success! User Activated Successfully.');
                }
                else {
                    return redirect()->route('user.client.index')->with('message', 'Success! User De-Activated Successfully.');
                }
            } 
            if ($status == 1) {
                return redirect()->route('users.index')->with('message', 'Success! User Activated Successfully.');
            } else {
                return redirect()->route('users.index')->with('message', 'Success! User De-Activated Successfully.');
            }
        }
      
    }
    

    public function CreateClientView(){
        return  view('admin.client.create'); 
    }


    public function offer_letter(){
     
    }


    public function approved(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'role'=>'required',
            'department' =>'required|numeric',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $user = User::findOrFail($id);
        $user->status = 1;
        $password = $request->password;
        $user->password = Hash::make($password);
        $user->department_id = $request->department;
        $user->syncRoles([$request->role]);
        $user->save();
        $url = url('users');
        return $this->success('success','',$url);
    }



    

    public function offer_letter_delete($id){
        $user =DB::table('users')->where('id',$id)->delete();
        if($user){
            return back()->with('message','Offer letter Deleted !!');
        }else{
            abort(503);
        }
    }


    public function  offer_letter_edit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                            'required',
                            'email',
                            Rule::unique('users')->ignore($id), // Correct usage of ignore method
                        ],
            'phone_no' => [
                            'required',
                            'numeric',
                            'digits:10',
                            Rule::unique('users')->ignore($id), // Correct usage of ignore method
                        ],
            'role' => 'required',
        ]);
    
        // Conditional Validation
        if ($request->has('before_ctc') && !is_null($request->before_ctc)) {
            $validator->addRules([
                'before_ctc' => 'required|numeric',
                'before_period' => 'required|numeric',
                'after_ctc' => 'required|numeric',
                'after_period' => 'required|numeric',
            ]);
        } else {
            $validator->addRules([
                'ctc' => 'required|numeric',
                'period' => 'required|numeric',
            ]);
        }
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'role' => $request->role,
            'ctc' => $request->ctc,
            'period' => $request->period,
            'before_ctc' => $request->before_ctc,
            'before_period' => $request->before_period,
            'after_ctc' => $request->after_ctc,
            'after_period' => $request->after_period,
            'date' => carbon::today(),
        ];

        // Render HTML view
        // return  view('admin.offer-letter.mail', compact('data'));
        $html = view('admin.offer-letter.mail', compact('data'))->render();

          // Debugging: Check the rendered HTML content
          if (empty($html)) {
            dd('HTML content is empty');
        }
    
        // dd($html); // This will dump the HTML content and stop the script execution

        // Generate PDF
        try {
            $pdf = PDF::loadHTML($html);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
 

        $currentYear = date('Y');
        $currentMonth = date('m');
        $directoryPath = "offer-letter/pdf/{$currentYear}/{$currentMonth}";

        // Format the current date and time for uniqueness
        $dateTime = date('Ymd_His'); // Format: 20240731_153212 (YearMonthDay_HourMinuteSecond)
        $pdfPath = $directoryPath . '/' . $request->name . '_offer_letter_' . $dateTime . '.pdf';

        // Ensure the directory exists, create if not
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true); // Create directory with full permissions
        }

        $pdf->save($pdfPath);

        // Email details
        $to = 'manjeetchand01@gmail.com';
        $cc = "hr@adxventure.com";
        $subject = 'We Welcome You to Adxventure Family! | Offer Letter';
        $name = strtoupper($request->name);
        $message = 'Hello ' . $name . ',

           <p>Thank you for exploring career opportunities with AdxVenture. You have
            completed  our initial selection process and we are pleased to offer you
            the employment.</p> <br>

            <p>Please find your offer cum Appointment letter.</p><br>

            <p>Documents required at the time of Joining</p></br>
            <p>1. Signed offer letter</p></br>
            <p>2. 2 photographs</p></br>
            <p> 3. Bank Account passbook copy</p></br>
            <p>4. Bank Account Details</p></br>
            <p>5. Adhar Card</p></br>
            <p>6. PAN Card</p></br>
            <p>7. 12 class marksheet.</p></br>
            <p>8. Graduation mark sheet</p></br>




           <p> Thank you</p></br>
           <p> Adventure</p></br>
           <p> 29, Tagore Villa Above Bank of Baroda</p></br>
            <p>Connaught Place, Dehradun</p></br>';
    
        $pdfPath1 = "offer-letter/Security.pdf";
        $pdfPath2 = "offer-letter/SOP-leave Policy.pdf";
        $files = [$pdfPath,$pdfPath1, $pdfPath2];
    
        // Headers
        $boundary = md5(uniqid(time()));
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
        $headers .= "From: Adxventure <no-reply@adxventure.com>\r\n";
        $headers .= "Cc: {$cc}\r\n";
    
        // Email Body
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n";
    
        // Attach files
        foreach ($files as $filePath) {
            $fileName = basename($filePath);
            $fileContent = file_get_contents($filePath);
            $fileContentEncoded = chunk_split(base64_encode($fileContent));
            
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/pdf; name=\"{$fileName}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
            $body .= $fileContentEncoded . "\r\n";
        }
    
        $body .= "--{$boundary}--";
    
        // Send email
        mail($to, $subject, $body, $headers);
    
        // Save to Database
        $updated = DB::table('users')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'offer_letter' => $pdfPath,
            'offer_letter_status' => 1,
            'user_id' => auth()->user()->id,
        ]);
        if($updated){
            // Redirect
            $url = route('offer.letter');
            return $this->success('Updated','',$url);
        }else{
            abort(503);
        }
    
    
    }


    
    
}
