<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\{Skill,User,Account,Document};
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index(){
        $user = auth()->user();
        if($user->hasRole(['Super-Admin','Admin','Human Resources Executive'])){
            $users = User::with(['account', 'document'])
             ->where(function ($query) {
                 $query->where('verification', 1)
                       ->orWhere('verification', 2);
             })
             ->whereNotIn('role_id', [5, 1])
             ->paginate(10);
             
            return view('admin.user.profile',compact('users'));
        }else{
            $user = User::with(['account', 'document','department'])->find(auth()->user()->id);
            $skills = Skill::get();
            return view('admin.user.profile-edit',compact('skills','user'));
        }
    }

    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'phone_no' => ['required', 'numeric', 'digits:10'],
            'pan_no' => ['required'],
            'aadhar_no' => ['required', 'numeric', 'digits:12'],
            'date_of_birth' => ['required'],
            'skills' => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
            'profile_image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'pan_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'aadhar_front_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'aadhar_back_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'passbook_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'account_holder_name' => ['required', 'string'],
            'bank_name' => ['required', 'string'],
            'account_no' => ['required', 'numeric','max_digits:15'],
            'ifsc' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
        ];
    
        // Validate the request
        $validator = \Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        // Update the user
        $user = User::findOrFail(auth()->user()->id);
        $data = $request->only([
            'name', 'email', 'phone_no', 'aadhar_no', 'pan_no', 'city', 'address', 'date_of_birth'
        ]);
        $data['skills'] = json_encode($request->skills); // Store skills as JSON
    
        // Handle profile image
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile', 'public');
        }
    
        $user->update($data);
    
        // Handle file uploads
        $filePaths = [];
       
        foreach (['aadhar_front_image', 'aadhar_back_image', 'passbook_image', 'pan_image'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $attach = $request->file($fileKey);
                $destinationPath = $fileKey . '/'; // Set the destination path based on the file key
                $fileName = time() . rand(1, 998587899) . '.' . $attach->getClientOriginalExtension();
                $attach->move($destinationPath, $fileName);
                $filePaths[$fileKey] = $fileName; // Store the file name in the $filePaths array
            }
        }
    
        // Create Account
        $account = Account::create([
            'user_id' => $user->id,
            'account_holder_name' => $request->account_holder_name,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc' => $request->ifsc,
        ]);
    
        // Create Document
        $document = Document::create([
            'user_id' => $user->id,
            'pan_img' => $filePaths['pan_image'] ?? '',
            'aadhar_front_img' => $filePaths['aadhar_front_image'] ?? '',
            'aadhar_back_img' => $filePaths['aadhar_back_image'] ?? '',
            'account_img' => $filePaths['passbook_image'] ?? '',
        ]);
    
        // Update user verification and associated IDs
        $user->update([
            'verification' => 1,
            'account_id' => $account->id,
            'document_id' => $document->id,
        ]);
        
            // Email details
            $to = 'manjeetchand01@gmail.com';
            $cc = 'hr@adxventure.com';
            $subject = 'Profile Approval Request';
            $name = ucfirst(htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8')); // Sanitizing user input

            // Email content
            $message = "
                <h3 style='color:black;'>Hello {$name}</h3>
                <p style='color:black;'>Please approve my profile.</p>
            ";

            // Headers
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Adxventure <no-reply@adxventure.com>\r\n";
            $headers .= "Cc: {$cc}\r\n";
            $headers .= "Reply-To: no-reply@adxventure.com\r\n"; // Optional: Reply-To header

            // Send email
            mail($to, $subject, $message, $headers);

        // Redirect to the dashboard
        $url = url('/dashboard');
        return $this->success('updated', '', $url);
    }
    
    
      
    public function edit(Request $request): View
    {
        return view('admin.user.profile');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
    
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();
        
        // Verify the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }
    
        // Update the password
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    
        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current_password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }

    public function verify($id,$status){
        $user = User::find($id);
        $user->verification = $status;
         // Email details
         $to = $user->email;
         $subject = 'Profile Approvad';
         $name = ucfirst(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8')); // Sanitizing user input

         // Email content
         $message = "
             <h3 style='color:black;'>Hello {$name}</h3>
             <p style='color:black;'>Your  Profile has Approved.</p>
         ";

         // Headers
         $headers = "MIME-Version: 1.0\r\n";
         $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
         $headers .= "From: Adxventure <no-reply@adxventure.com>\r\n";
         $headers .= "Reply-To: no-reply@adxventure.com\r\n"; 
         // Send email
         mail($to, $subject, $message, $headers);
        $user->save();
        return redirect()->back()->with('message',' Profile verified successfully.');
    }
}
