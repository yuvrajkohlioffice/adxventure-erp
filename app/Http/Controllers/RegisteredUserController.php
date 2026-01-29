<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {   
        
         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_no' => ['required', 'numeric', 'digits:10', 'unique:' . User::class],
            'skills' => ['required', 'string'],
            'profile_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Assuming it's an image upload field with specific mime types and size limit
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // dd($request->all());
    
        if($request->profile_image){
            $attach = $request->profile_image;
            $destinationPath = 'profile/'; // Set your desired destination path
            $image = time().rand(1,998587899) . '.' . $attach->getClientOriginalExtension();
            $attach->move($destinationPath, $image);
        }

        $user = User::create([
            'name' => $request->name,
            'skills' => $request->skills,
            'phone_no' => $request->phone_no,
            'image' => $image,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        if($user){
            $message = "You Registered successfully! Please wait for your profile approval.";
        }else{
            $message = "Error! Please try again after sometime.";
        }
        
        return redirect()->to('login')->with('message',$message);

    }
}