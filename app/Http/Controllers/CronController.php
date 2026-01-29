<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Projects,Tasks,User,Reports,TaskDates,TaskUser,TaskTiming,ProjectUser,DailyReport,Api};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Http;
use Auth;
use Illuminate\Support\Facades\Log;
require_once app_path('Helpers/helpers.php'); // ✅ Include helper here

class CronController extends Controller
{
    
    public function login_reminder(){   
        $users = User::whereNotIn('role_id', ['5', '1'])
                ->where('is_active', 1)
                ->whereDoesntHave('today_late')
                ->whereDoesntHave('today_leave')
                ->get();
        
        if ($users->count() > 0) {
            foreach($users as $user){
                $subject = "Login Reminder - Please log in to your account";
                $senderRole = $user->roles()->first()->name; 
                $header = "Login Reminder";
                $message = "
                    <p>Dear <strong>{$user->name}</strong>,<br> ({$senderRole})</p>
                    <p>We noticed that you haven’t logged in today.</br>
                    This is a gentle reminder to log in to your account to mark your attendance.</br>
                    If you are facing any issues with login, please contact the HR department immediately.</p>
                ";

                $recipients = $user->email;
                sendMail($recipients, $subject, $header, $footer  = null, $message);
            }
            Log::info('Login reminder sent successfully.');
        } else {
            Log::info('No users found for login reminder.');
        }
    }

    public function admin_login_mail(){
        $users = User::whereNotIn('role_id', ['5', '1'])
            ->where('is_active', 1)
            ->whereHas('today_late')
            ->get();    

        // Check if any users found
        if ($users->isEmpty()) {
            Log::info('No users found for login reminder.');
            return;
        }

        $today = now()->format('d M Y');
        $subject = "Login Employee - {$today}";
        $header = "Login Employee";

        // Build table rows
        $tableRows = '';
        foreach ($users as $user) {
            $roleName    = $user->roles->pluck('name')->implode(', ');
            $lateReason  = optional($user->today_late)->reason ?? 'On time';
            $loginTime   = optional($user->today_late)->login_time ?? 'On time';
            $phoneNumber = preg_replace('/\D/', '', $user->phone_no); 
            $message     = urlencode("Hello {$user->name}, I’ve noticed your login time today ({$loginTime}). Please make sure to log in on time going forward.");

            $tableRows .= "
                <tr>
                    <td style='padding:8px;border:1px solid #ddd;'>{$user->name} <small>({$roleName})</small></td>
                    <td style='padding:8px;border:1px solid #ddd;'>{$loginTime}</td>
                    <td style='padding:8px;border:1px solid #ddd;'>{$lateReason}</td>
                    <td style='padding:8px; border:1px solid #ddd; text-align:center;'>
                        <a href='https://wa.me/+91{$phoneNumber}?text={$message}' target='_blank' style='text-decoration:none; cursor:pointer;'>✉️</a>
                    </td>
                </tr>
            ";
        }

        // Build the HTML table
        $messageBody = "
            <p style='font-family:Arial,sans-serif;color:#555;'>
                The following employees were late today ({$today}):
            </p>
            <table style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;'>
                <thead>
                    <tr style='background-color:#f4f4f4;'>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Employee Name</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Login Time</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Late Reason</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Send Message</th>
                    </tr>
                </thead>
                <tbody>
                    {$tableRows}
                </tbody>
            </table>
        ";


        // $api = Api::first();

        // // Build message lines
        // $lines = [];
        // foreach ($users as $user) {
        //     $roleName = $user->roles->pluck('name')->implode(', ');
        //     $lateReason = optional($user->today_late)->reason ?? 'No reason provided';
        //     $lines[] = "{$user->name} ({$roleName}) => {$lateReason}";
        // }

        // // Combine lines into one text message
        // $messageBody = "Hello Admin,\nThe following users were late today:\n\n" . implode("\n", $lines);


        // // Send single message
        // $params = [
        //     'recipient' => '919997294527', // admin number
        //     'apikey'    => $api->key,
        //     'text'      => $messageBody,
        // ];


        // $queryString = http_build_query($params);
        // $apiUrl = $api->url;
        // $url = "{$apiUrl}?{$queryString}";
        //     $response = Http::get($url);
        // if ($response->successful()) {
        //     Log::info('Login reminder sent successfully to admin.');
        // } else {
        //     Log::warning('Failed to send login reminder to admin.');
        // }
           // Send email
        sendMail('suyalvikas@gmail.com', $subject, $header, null, $messageBody);

        Log::info('Login reminder email sent successfully to admin.');

    }


    public function report_warning(){
        
    }
}