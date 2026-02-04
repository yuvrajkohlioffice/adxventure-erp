<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\CronController;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\LogoutUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // ---------------------------------------------------------------------
        // 1. Employee Login Reminder
        // ---------------------------------------------------------------------
        // Sends an email reminder to employees who haven't logged in yet.
        // Schedule: Monday to Saturday at 09:45 AM (Excludes Sunday)
        $schedule->call(function () {
            (new CronController)->login_reminder();
        })
        ->days([1, 2, 3, 4, 5, 6]) // 1=Mon, 2=Tue, ... 6=Sat (0 is Sunday)
        ->at('09:45');

        // ---------------------------------------------------------------------
        // 2. Admin Late Attendance Report
        // ---------------------------------------------------------------------
        // Sends a report to the Admin with a list of late employees.
        // Schedule: Monday to Saturday at 10:00 AM (Excludes Sunday)
        $schedule->call(function () {
            (new CronController)->admin_login_mail();
        })
        ->days([1, 2, 3, 4, 5, 6])
        ->at('10:00');

        // ---------------------------------------------------------------------
        // 3. Auto-Logout (Optional/Commented)
        // ---------------------------------------------------------------------
        // Force logout users at 8:00 PM daily
        // $schedule->command('users:logout')->dailyAt('20:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        // Load commands from the Commands directory
        $this->load(__DIR__.'/Commands');

        // Register console routes
        require base_path('routes/console.php');
    }
}