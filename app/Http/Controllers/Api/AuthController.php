<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\{User,LateReason,Lead,Followup};
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email_phone' => [
                'required',
                function ($attribute, $value, $fail) {
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isPhone = preg_match('/^\d{10,15}$/', $value);
                    if (!$isEmail && !$isPhone) {
                        $fail('Please enter a valid email address or phone number.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:6', 'max:20'],
        ]);

        $emailOrPhone = $request->email_phone;
        $throttleKey = Str::lower($emailOrPhone) . '|' . $request->ip();
        $loginType = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Optional: Throttle login attempts (Laravel built-in protection)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many login attempts. Please try again later.',
            ], 429);
        }

        if (Auth::attempt([$loginType => $emailOrPhone, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->is_active != 1) {
                Auth::logout();
                RateLimiter::clear($throttleKey);
                throw ValidationException::withMessages([
                    'email_phone' => ['User is not active.'],
                ]);
            }

            RateLimiter::clear($throttleKey);
            $token = $user->createToken('API Token')->plainTextToken;
            $user['role'] = $user->roles->first()->name;
            $times =  LogHelper::getLoginLogoutTimes($user->id);
            $user['login_time'] =  $times['login_time'] ?? 'Not Available' ;
            $user['logout_time'] =  $times['logout_time'] ?? 'Not Available' ;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'data' => $user,
            ]);
        } else {
            RateLimiter::hit($throttleKey, 60);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }
    }


    public function late_reason(Request $request){

        $request->validate ([
            'login_time' => 'required',
            'reason' => 'nullable|string|min:5|max:100',
        ]);

        try{
            if($request->reason){
                LateReason::create([
                    'user_id' => auth()->user()->id,
                    'login_time' => $request->login_time,    
                    'reason' => $request->reason,
                    'status' => 1,
                ]);
            }else{
                LateReason::create([
                    'user_id' => auth()->user()->id,
                    'login_time' => $request->login_time,    
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reason submit successfully.',
                // 'error' => $e->getMessage(),
            ], 500);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving leads.',
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function logout(Request $request){
        
    }

    public function user_profile(){
        $user = User::select(['id', 'name', 'email', 'phone_no', 'date_of_joining', 'image', 'city'])->find(auth()->user()->id);
        $user['role'] = $user->roles->first()->name;
        $times =  LogHelper::getLoginLogoutTimes($user->id);
        $user['login_time'] =  $times['login_time'] ?? 'Not Available' ;
        $user['logout_time'] =  $times['logout_time'] ?? 'Not Available' ;
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'User successfully retrived.',
                'data' => $user,
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 400);
        }
    }


    public function dashboard(){
        $user = auth()->user();
        if ($user && $user->hasRole(['BDE', 'Business Development Intern'])) {

            $count = [
                //leads
                'leads'     => Lead::where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->count(),
                'today_leads' => Lead::where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->whereDate('created_at',Carbon::today())->count(),

                //followups
                'followups' => Followup::whereHas('lead')->where('user_id',$user->id)->count(),
                'today_followups' => Followup::whereHas('lead')->where('user_id',$user->id)->whereDate('created_at',Carbon::today())->count(),

                //quotation
                'quotation' => Lead::where('quotation', 1)->where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->count(),
                'today_quotation' => Lead::where('quotation', 1)->where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->whereDate('quotation_date',Carbon::today())->count(),

                //proposal
                'proposal'  => Lead::where('proposal', 1)->where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->count(),
                'today_quotation' => Lead::where('proposal', 1)->where('user_id',$user->id)->orWhere('assigned_user_id',$user->id)->whereDate('proposal_date',Carbon::today())->count(),

                'converted_clients' => 0,
                'today_converted_clients' => 0,
            ];

        }else{

            $count = [
                //leads
                'leads'     => Lead::count(),
                'today_leads' => Lead::whereDate('created_at',Carbon::today())->count(),

                //followups
                'followups' => Followup::whereHas('lead')->count(),
                'today_followups' => Followup::whereHas('lead')->whereDate('created_at',Carbon::today())->count(),

                //quotation
                'quotation' => Lead::where('quotation', 1)->count(),
                'today_quotation' => Lead::where('quotation', 1)->whereDate('quotation_date',Carbon::today())->count(),

                //proposal
                'proposal'  => Lead::where('proposal', 1)->count(),
                'today_quotation' => Lead::where('proposal', 1)->whereDate('proposal_date',Carbon::today())->count(),

                'converted_clients' => 0,
                'today_converted_clients' => 0,
            ];
        }
     

        if($count){
            return response()->json([
                'success' => true,
                'message' => 'Data successfully retrieved.',
                'data' => $count,
            ], 200);
        }
          return response()->json([
            'success' => false,
            'message' => 'Something went wrong while retrieving data.',
        ], 500);
    }      
}
