<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class CronController extends Controller
{
    // Define ignored roles (e.g., 1=Admin, 5=SuperAdmin)
    private const IGNORED_ROLES = ['1', '5'];

    /**
     * Send reminders to employees who have not logged in yet.
     */
    public function login_reminder(): void
    {
        Log::info("Cron Started: Login Reminder");

        try {
            $users = User::with('roles')
                ->whereNotIn('role_id', self::IGNORED_ROLES)
                ->where('is_active', 1)
                ->whereDoesntHave('today_late')  // No attendance record
                ->whereDoesntHave('today_leave') // No leave record
                ->get();

            if ($users->isEmpty()) {
                Log::info('Login Reminder: No pending users found.');
                return;
            }

            foreach ($users as $user) {
                $roleName = $user->roles->first()?->name ?? 'Employee';
                
                $subject = "Login Reminder - Please log in to your account";
                $header  = "Login Reminder";
                $message = $this->buildReminderHtml($user->name, $roleName);

                // Using the helper function
                sendMail($user->email, $subject, $header, null, $message);
            }

            Log::info("Login Reminder: Sent to {$users->count()} users.");

        } catch (Exception $e) {
            Log::error("Login Reminder Error: " . $e->getMessage());
        }
    }

    /**
     * Send a daily report to the Admin containing a list of late employees.
     */
    public function admin_login_mail(): void
    {
        Log::info("Cron Started: Admin Late Report");

        try {
            $users = User::with(['roles', 'today_late'])
                ->whereNotIn('role_id', self::IGNORED_ROLES)
                ->where('is_active', 1)
                ->has('today_late') // Only get users who have a 'late' record
                ->get();

            if ($users->isEmpty()) {
                Log::info('Admin Late Report: No late employees today.');
                return;
            }

            $todayDate  = now()->format('d M Y');
            $subject    = "Login Employee - {$todayDate}";
            $header     = "Late Attendance Report";
            $adminEmail = 'suyalvikas@gmail.com';

            // Build HTML
            $messageBody = $this->buildAdminReportHtml($users, $todayDate);

            // Send Email
            sendMail($adminEmail, $subject, $header, null, $messageBody);

            Log::info("Admin Late Report: Sent successfully.");

        } catch (Exception $e) {
            Log::error("Admin Late Report Error: " . $e->getMessage());
        }
    }

    /**
     * Placeholder for Report Warning Logic
     */
    public function report_warning()
    {
        // Logic for warning reports goes here
    }

    // -------------------------------------------------------------------------
    // Private Helpers (HTML Generators)
    // -------------------------------------------------------------------------

    private function buildReminderHtml($name, $role): string
    {
        return "
            <p>Dear <strong>{$name}</strong> <small>({$role})</small>,</p>
            <p>We noticed that you haven't logged in today.<br>
            This is a gentle reminder to log in to your account to mark your attendance.<br>
            If you are facing any issues with login, please contact the HR department immediately.</p>
        ";
    }

    private function buildAdminReportHtml($users, $date): string
    {
        $rows = '';

        foreach ($users as $user) {
            $roleName    = $user->roles->pluck('name')->implode(', ');
            $lateReason  = $user->today_late?->reason ?? 'On time';
            $loginTime   = $user->today_late?->login_time ?? 'On time';
            
            $phoneNumber = preg_replace('/\D/', '', $user->phone_no); 
            $waMessage   = urlencode("Hello {$user->name}, I’ve noticed your login time today ({$loginTime}). Please make sure to log in on time going forward.");

            $rows .= "
                <tr>
                    <td style='padding:8px;border:1px solid #ddd;'>
                        {$user->name} <br><small style='color:#666;'>{$roleName}</small>
                    </td>
                    <td style='padding:8px;border:1px solid #ddd;'>{$loginTime}</td>
                    <td style='padding:8px;border:1px solid #ddd;'>{$lateReason}</td>
                    <td style='padding:8px; border:1px solid #ddd; text-align:center;'>
                        <a href='https://wa.me/91{$phoneNumber}?text={$waMessage}' target='_blank' style='text-decoration:none; font-size:16px;'>✉️ WhatsApp</a>
                    </td>
                </tr>
            ";
        }

        return "
            <p style='font-family:Arial,sans-serif;color:#555;'>
                The following employees were late today ({$date}):
            </p>
            <table style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;'>
                <thead>
                    <tr style='background-color:#f4f4f4;'>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Employee</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Time</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Reason</th>
                        <th style='padding:8px;border:1px solid #ddd;text-align:left;'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>
        ";
    }
}