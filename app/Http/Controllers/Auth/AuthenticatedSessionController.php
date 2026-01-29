<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Logs;
use App\Models\LateReason;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
require_once app_path('Helpers/helpers.php'); // ✅ Include helper here


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function authenticate(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'email' => ['required', 'string', 'email'],
    //         'password' => ['required', 'string'],
    //     ]);
   
    //     // Ensure the request is not rate-limited
    //     $this->ensureIsNotRateLimited($request);

    //     // Attempt to authenticate the user
    //     if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember')))
    //     {
    //         // Record the failed attempt
    //         RateLimiter::hit($this->throttleKey($request));

    //         // Throw validation exception
    //         throw ValidationException::withMessages([
    //             'email' => trans('auth.failed'),
    //         ]);
    //     }

    //     // Clear rate limiter on successful authentication
    //     RateLimiter::clear($this->throttleKey($request));

    //     // Check user status
    //     $user = Auth::user();

    //     if($user->is_active != 1 )
    //     {
    //         // Log out the user if their status is not 1
    //         Auth::logout();

    //         // Optionally, record the failed login attempt for user status issue
    //         RateLimiter::hit($this->throttleKey($request));

    //         // Throw validation exception with a custom message
    //         throw ValidationException::withMessages([
    //             'email' => trans('User Not Active'),
    //         ]);
    //     }

    //     // Regenerate session
    //     $request->session()->regenerate();

    //     // Log the login activity
    //     Logs::LoginLogsCreate($user->id, 1, 'Login session started');
    //     // Redirect to the intended page or default route

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }

    public function authenticate(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    
        $this->ensureIsNotRateLimited($request);
    
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    
        RateLimiter::clear($this->throttleKey($request));
    
        $user = Auth::user();
        if ($user->is_active != 1) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => trans('User Not Active'),
            ]);
        }
    
        $request->session()->regenerate();
    
        // Get the current time and today's date
        $loginTime = now();
        $currentDate = $loginTime->format('Y-m-d');
    
        // Check if the user has already submitted a late reason today
        $lateReasonExists = LateReason::where('user_id', $user->id)
            ->whereDate('created_at', $currentDate)
            ->exists();
    
        // Check if the user has logged in before 8:55 AM
        if ($loginTime->hour < 8 || ($loginTime->hour == 8 && $loginTime->minute < 55)) {
            throw ValidationException::withMessages([
                'email' => trans('You cannot log in before 8:55 AM.'),
            ]);
        }
    
        // Check if the user has logged in before 9 AM on the same day (track first login)
        $firstLoginBefore9AM = $request->session()->get('first_login_before_9am');
        $cutoff = now()->setTime(9, 30);

        // If no login before 9:30 AM is recorded, check if they are logging in before 9:30
        if (!$firstLoginBefore9AM && $loginTime->lt($cutoff)) {
            // Record that the user logged in before 9:30 AM
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

            $platform = 'Unknown OS';
            if (str_contains($agent, 'windows')) {
                $platform = 'Windows';
            } elseif (str_contains($agent, 'mac os') || str_contains($agent, 'macintosh')) {
                $platform = 'Mac';
            } elseif (str_contains($agent, 'android')) {
                $platform = 'Android';
            } elseif (str_contains($agent, 'iphone')) {
                $platform = 'iPhone';
            } elseif (str_contains($agent, 'ipad')) {
                $platform = 'iPad';
            } elseif (str_contains($agent, 'linux')) {
                $platform = 'Mobile';
            }
            
            LateReason::create([
                'device' => $platform,
                'ip_address' => request()->ip(),
                'user_id'    => Auth::id(),
                'login_time' => $loginTime,
                'created_at' => now(),
            ]);

            $request->session()->put('first_login_before_9am', true);
            $request->session()->forget('show_late_modal'); // Don't show modal if logging in before 9:30
        }

        // If the user logs in after 9:30 AM and hasn't submitted a late reason for the day
        if ($loginTime->gt($cutoff) && !$lateReasonExists) {
            if ($firstLoginBefore9AM) {
                $request->session()->forget('show_late_modal'); // Don't show modal
            } else {
                $request->session()->put('show_late_modal', true);
                $request->session()->put('login_time', $loginTime->toTimeString());
            }
        }
        
    
        Logs::LoginLogsCreate($user->id, 1, 'Login session started');
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }
    

    

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /**
     * Destroy an authenticated session.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     Logs::LoginLogsCreate(auth()->user()->id, 2, 'Login session ended');

    //     $late = LateReason::where('user_id', auth()->user()->id)
    //     ->whereDate('created_at', Carbon::today()) // Ensure proper date filtering
    //     ->latest()
    //     ->first();
    
    //     if ($late) {
    //         $loginTime = Carbon::parse($late->login_time);
    //         $working_hrs = $loginTime->diff(now())->format('%H:%I:%S'); 
    //         $late->update([
    //             'logout_time' => now(),
    //             'working_hrs' => $working_hrs
    //         ]);
    //     }
    
        
    //     Auth::guard('web')->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return redirect('/');
    // }

   

 
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Logs::LoginLogsCreate(auth()->user()->id, 2, 'Login session ended');

        $late = LateReason::where('user_id', auth()->user()->id)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        if ($late) {
            $loginTime = Carbon::parse($late->login_time);
            $now = now();
            $diffInSeconds = $loginTime->diffInSeconds($now);
            $formattedWorkingHrs = gmdate('H:i:s', $diffInSeconds);

            if ($diffInSeconds < 9 * 3600) {
                if ($request->type == 1) {
                    $late->update([
                        'logout_time' => $now,
                        'working_hrs' => $formattedWorkingHrs,
                    ]);
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/');
                } else {
                    return response()->json([
                        'type' => 1,
                        'message' => "Oops! Your working hours aren't quite complete yet. 😞 Please make sure you finish your hours. If you decide to log out now, you'll lose 1 day of work. Are you sure you want to proceed?",
                        'working_hrs' => $formattedWorkingHrs,
                    ]);
                }
            } else {
                $agent = strtolower($_SERVER['HTTP_USER_AGENT']);


                $platform = 'Unknown OS';
                if (str_contains($agent, 'windows')) {
                    $platform = 'Windows';
                } elseif (str_contains($agent, 'mac os') || str_contains($agent, 'macintosh')) {
                    $platform = 'Mac';
                } elseif (str_contains($agent, 'android')) {
                    $platform = 'Android';
                } elseif (str_contains($agent, 'iphone')) {
                    $platform = 'iPhone';
                } elseif (str_contains($agent, 'ipad')) {
                    $platform = 'iPad';
                } elseif (str_contains($agent, 'linux')) {
                    $platform = 'Mobile';
                }

                $late->update([
                    // 'device' => $platform,
                    // 'ip_address' => request()->ip(),
                    'logout_time' => $now,
                    'working_hrs' => $formattedWorkingHrs,
                ]);
                
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/');
            }
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function submitLateReason(Request $request)
    {
        // Validate the request
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
    
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);


        $platform = 'Unknown OS';
        if (str_contains($agent, 'windows')) {
            $platform = 'Windows';
        } elseif (str_contains($agent, 'mac os') || str_contains($agent, 'macintosh')) {
            $platform = 'Mac';
        } elseif (str_contains($agent, 'android')) {
            $platform = 'Android';
        } elseif (str_contains($agent, 'iphone')) {
            $platform = 'iPhone';
        } elseif (str_contains($agent, 'ipad')) {
            $platform = 'iPad';
        } elseif (str_contains($agent, 'linux')) {
            $platform = 'Mobile';
        }


        // Save the reason to the database
        LateReason::create([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'login_time' => session('login_time'),
            'reason' => $request->reason,
            'status' => 1,
            'device' => $platform,
            'created_at' => now(),
        ]);

        $startDate = Carbon::now()->startOfMonth()->toDateString(); 
        $endDate   = Carbon::now()->endOfMonth()->toDateString();

        $count = LateReason::with('user')
            ->where('user_id', Auth::id())
            ->whereNotNull('status')
            ->whereBetween('created_at', [$startDate, $endDate])  
            ->count();

        $subject = "Late Arrival Notification – ". auth()->user()->name;
        $header = "Late Arrival Report";
        $date = now()->format('d M Y');
        $scheduled_time = '09:30 AM';
        $arrival_time = session('login_time');
        $delay_duration = gmdate('H:i:s', strtotime($arrival_time) - strtotime($scheduled_time));
        $reason = $request->reason ?? 'Not specified';
        $count = $count ?? 1;

        // =============================
        // 📩 HR Mail
        // =============================
        $message = "
            <p>Dear HR Team,</p>
            <p>This is to inform you that <strong>" . auth()->user()->name . " (" . auth()->user()->roles()->first()->name . ")</strong> arrived late today.</p>
            <p><strong>Date:</strong> {$date}<br>
            <strong>Scheduled Time:</strong> {$scheduled_time}<br>
            <strong>Arrival Time:</strong> {$arrival_time}<br>
            <strong>Delay Duration:</strong> {$delay_duration}<br>
            <strong>Reason:</strong> {$reason}<br>
            <strong>Late Count:</strong> {$count}</p>
            <p>Please take note of this for attendance records.</p>
        ";

        // HR recipients
        $to = [
            'manjeetchand01@gmail.com',
            'priyanka@adxventure.com',
            'hr@adxventure.com',
            'suyalvikas@gmail.com',
            'work@adxventure.com'
        ];
        $recipients = implode(',', $to);
        // Send mail to HR
        sendMail($recipients, $subject, $header,$footer= null, $message);
        // =============================
        // 👤 Employee Mail
        // =============================

        $user_to = auth()->user()->email;
        $user_name = auth()->user()->name;
        $role = auth()->user()->roles()->first()->name;
        $count_display = addOrdinalSuffix($count);

        if ($count >= 3) {
            // ⚠️ If late 3 or more times
            $user_message = "
                <p>Dear <strong>{$user_name}</strong>,<br> {$role}</p>
                <p>We noticed that you have been late <strong>{$count_display}</strong> times this month. 
                As per company policy, being late three or more times may be considered a 
                <strong>half-day deduction</strong>.</p>

                <p>Please make efforts to arrive on time. 
                Punctuality reflects professionalism and helps maintain a positive workflow. 
                We appreciate your contributions and believe in your commitment to improvement.</p>

                <p>Keep giving your best!</p>
            ";
        } else {
            // 🕒 Gentle reminder if late < 3 times
            $user_message = "
                <p>Dear <strong>{$user_name}</strong>,<br> {$role}</p>
                <p>This is to inform you that you arrived late today, on <strong>{$date}</strong>.</p>

                <p><strong>Scheduled Time:</strong> {$scheduled_time}<br>
                <strong>Arrival Time:</strong> {$arrival_time}<br>
                <strong>Delay Duration:</strong> {$delay_duration}<br>
                <strong>Reason:</strong> {$reason}</p>

                <p>Please make sure to reach the office on time. 
                Consistent punctuality supports better discipline and teamwork.</p>
            ";
        }
        // Send to employee
        sendMail($user_to, $subject, $header,$footer= null, $user_message);
        // Mark the late reason as submitted for today
        $request->session()->put('late_reason_submitted', true);
    
        // Clear the session variable that shows the modal
        $request->session()->forget('show_late_modal');
    
        // Return success response
        return response()->json(['success' => true]);
    }
}
