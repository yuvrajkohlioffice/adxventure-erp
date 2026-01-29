<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Department,CustomRole,Candidate};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use PDF;
use Illuminate\Support\Facades\File;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class CandidateController extends Controller
{

    public function index(){

        $roles = CustomRole::orderBy('name','asc')->get(['name']);
        $users = Candidate::with('user')->orderBy('name','asc')->paginate(20);
        $departments = Department::get();
        return view('admin.candidates.index',compact('users','roles','departments'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'phone' => 'required|numeric|unique:candidates,phone|unique:candidates,phone|digits:10',
            'email' => 'required|email|unique:candidates,email',
            'date_of_birth' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $candidate =  new Candidate ();
        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->dob = $request->date_of_birth;
        $candidate->user_id = auth()->user()->id;
        if($request->intern == 1){
            $candidate->intern = 1;
        }
        if($candidate->save()){
            $url = url('/candidates');
            return $this->success('add','',$url);
        }else{
            abort(503);
        }
    }
   
    public function interview(Request $request)
    {
        $round = $request->input('round');
        $userId = $request->input('id');
        $candidate = Candidate::findOrFail($userId);
    
        if (in_array($round, [1, 2, 3])) {
            $candidate->interview = $round;
            if ($candidate->save()) {
                return response()->json(['success' => true]);
            } else {
                abort(503);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid round'], 400);
        }
    }

    public function genrate(Request $request) {
        $id = $request->id;
        $candidate = Candidate::findOrFail($id);
        if($candidate) {
            return response()->json([
                'success' => true,
                'candidate' => $candidate
            ], 200); 
        } else {
            abort(503);
        }
    }


    public function offer_letter(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
            'role' => 'required',
        ]);

        // Conditional Validation
        if ($request->has('before_ctc') && !is_null($request->before_ctc)) {
            $validator->sometimes('before_ctc', 'required|numeric', fn() => true);
            $validator->sometimes('before_period', 'required|numeric', fn() => true);
            $validator->sometimes('after_ctc', 'required|numeric', fn() => true);
            $validator->sometimes('after_period', 'required|numeric', fn() => true);
        } else {
            $validator->sometimes('ctc', 'required|numeric', fn() => true);
            $validator->sometimes('period', 'required|numeric', fn() => true);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $offer = Candidate::findOrFail($id);
        $offer->name = $request->name;
        $offer->email = $request->email;
        $offer->phone = $request->phone;
        $offer->phone = $request->phone;
        $offer->role = $request->role;
        $offer->ctc = $request->ctc;
        $offer->ctc_period = $request->period;
        $offer->before_ctc = $request->before_ctc;
        $offer->before_ctc_period = $request->before_period;
        $offer->after_ctc = $request->after_ctc;
        $offer->after_ctc_period = $request->after_period;

        if ($offer->save()) {
            // Generate HTML for the PDF
            $data = $offer->toArray();
            $html = view('admin.candidates.mail', compact('data'))->render();
            if (empty($html)) {
                abort(503);
            }

            // Generate PDF
            try {
                $pdf = PDF::loadHTML($html);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }

            // Set the file path
            $currentYear = date('Y');
            $currentMonth = date('m');
            $directoryPath = "offer-letter/pdf/{$currentYear}/{$currentMonth}";
            $dateTime = date('Ymd_His');
            $pdfPath = $directoryPath . '/' . $offer->name . '_offer_letter_' . $dateTime . '.pdf';

            // Ensure the directory exists
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            // Save the PDF
            $pdf->save($pdfPath);

            // Prepare email details
            $to = $request->email;
            $cc = "hr@adxventure.com";
            $subject = 'We Welcome You to Adxventure Family! | Offer Letter';
            $name = ucfirst($offer->name);
            $message = '<h3>Hello ' . $name . '</h3>
                <p>Thank you for exploring career opportunities with AdxVenture. You have completed our initial selection process and we are pleased to offer you the employment.</p> 
                <p>Please find your offer cum Appointment letter attached.</p>
                <p  style="color:black">Documents required at the time of Joining</p>
                <p style="color:black">1. Signed offer letter</p>
                <p style="color:black">2. 2 photographs</p>
                <p style="color:black"> 3. Bank Account passbook copy</p>
                <p style="color:black">4. Bank Account Details</p>
                <p style="color:black">5. Adhar Card</p>
                <p style="color:black">6. PAN Card</p>
                <p style="color:black">7. 12 class marksheet.</p>
                <p style="color:black">8. Graduation mark sheet</p><br>




                <p style="color:black"> Thank you</p>
                <p style="color:black"> Adventure</p>
                <p style="color:black"> 29, Tagore Villa Above Bank of Baroda</p>
                <p style="color:black">Connaught Place, Dehradun</p>';
            

            // Attachments
            $pdfPath1 = "offer-letter/Security.pdf";
            $pdfPath2 = "offer-letter/SOP-leave Policy.pdf";
            $files = [$pdfPath, $pdfPath1, $pdfPath2];

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

            // Send email using PHP's mail function
            mail($to, $subject, $body, $headers);

            $offer->offer_letter = $pdfPath;
            $offer->save();
            $url = url('candidates');
            return $this->success('success', '', $url);
        } else {
            abort(503);
        }
    }


    public function show($id)
    {
        $candidate = Candidate::findOrFail($id);
        if ($candidate) {
            $candidate->delete();
            return response()->json(['success' => true, 'message' => 'Candidate Deleted'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Candidate Not Found'], 404);
        }
    }

    public function add_employee(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'candidate_id' => 'required|numeric',
            'department'  => 'required|numeric',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if($request->intern ==1){
            $validator = Validator::make($request->all(), [
                'role' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        // Retrieve the candidate by ID
        $candidate = Candidate::find($request->candidate_id);
        if($request->intern ==1){
            $roles = DB::table('roles')->where('name',$request->role)->first();
        }else{
            $roles = DB::table('roles')->where('name',$candidate->role)->first();
        }

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found.'], 404);
        }
    
        // Create a new user
        $user = new User();
        $user->name = $candidate->name;
        $user->email = $candidate->email;
        $user->phone_no = $candidate->phone;
        $user->department_id = $request->department;
        $user->date_of_birth = $candidate->dob;
        if($request->intern ==1){
            $user->assignRole($request->role);
        }else{
            $user->assignRole($candidate->role); 
        }
        $user->role_id = $roles->id;
        $user->user_id = auth()->user()->id;
        $user->date_of_joining = Carbon::today();
        $user->is_active = 1;

        // Handle file upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image'); // Get the uploaded file
    
            // Define the destination path
            $destinationPath = 'profile/';
    
            // Ensure the directory exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
    
            // Generate a unique filename
            $timestamp = now()->format('YmdHis'); // Current date and time
            $filename = $candidate->name . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
    
            // Store the filename in the user model
            $user->image = $filename;
    
            // Move the file to the destination path with the new name
            try {
                $file->move($destinationPath, $filename);
            } catch (\Exception $e) {
                return response()->json(['error' => 'File could not be uploaded.'], 500);
            }
        }
    
        // Function to generate password with prefix
        $password = $this->generatePassword($candidate->name);
    
        // Hash the password
        $user->password = Hash::make($password);


        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: tms@adxventure.com" . "\r\n";
        $headers .= "Cc: hr@adxventure.com" . "\r\n"; // CC header
        
        $to = $user->email;
        $subject = 'Employee Registration | Adxventure';
        $name = $user->name;
        $loginUrl = asset('login');
        
        $html = '<html>
            <head>
                <title>' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</title>
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
                                        <p style="font-size: 18px; margin-bottom: 20px;">Dear ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>
                                        <p style="font-size: 16px;">Congratulations on becoming a part of Adxventure!</p>
                                        <p style="font-size: 16px;">We are thrilled to have you as our valued Employee , and we appreciate your trust in us.</p>
                                        <p style="font-size: 16px;">To get started, please check your account by clicking on the following link:</p>
                                        <a href="' . htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') . '" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;">
                                            Account Login URL
                                        </a>
                                        <h5>Login Credentials</h5>
                                        <p>Email: ' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '</p>
                                        <p>Password: ' . htmlspecialchars($password, ENT_QUOTES, 'UTF-8') . '</p>
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
        $candidate->status =1;
        if($request->intern ==1){
            $candidate->role = $request->role;
        }
        $candidate->save();
        // Save the user
        if ($user->save()) {
            return response()->json(['success' => 'Employee added successfully.']);
        } else {
            return response()->json(['error' => 'Failed to add user.'], 500);
        }
    }
    
    private function generatePassword($name)
    {
        // Fetch the current prefix value from the database
        $prefixRecord = DB::table('password')->first();
    
        // Check if the settings table is empty and insert a default record if necessary
        if (!$prefixRecord) {
            DB::table('password')->insert([
                'password_prefix' => '05', // Default prefix value
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $prefix = '05'; // Default prefix if not found
        } else {
            $prefix = $prefixRecord->password_prefix;
        }
    
        // Increment the prefix
        $newPrefix = str_pad((int)$prefix + 1, 2, '0', STR_PAD_LEFT);
    
        // Update the prefix value in the database
        DB::table('password')->update(['password_prefix' => $newPrefix]);
    
        // Generate the password with the incremented prefix and the first 3 letters of $name
        $shortName = strtolower(substr($name, 0, 3)); // First 3 letters of the name
        return 'adx' . $newPrefix . $shortName; // Combine prefix and name
    }
}