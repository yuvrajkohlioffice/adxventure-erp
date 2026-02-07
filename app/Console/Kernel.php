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
        \App\Console\Commands\SendBdeDailyReport::class, // Ensure your new command is registered
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ---------------------------------------------------------------------
        // MONDAY TO SATURDAY SCHEDULE (Excluding Sunday)
        // ---------------------------------------------------------------------
        // Using a group applies the days filter to all commands inside
        // Days: 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
        
        $schedule->group(function (Schedule $schedule) {
            
            // 1. Employee Login Reminder - 09:45 AM
            // Reminds employees who haven't logged in.
            $schedule->call([CronController::class, 'login_reminder'])
                     ->at('09:45');

            // 2. Admin Late Attendance Report - 10:00 AM
            // Sends a list of late employees to Admin.
            $schedule->call([CronController::class, 'admin_login_mail'])
                     ->at('10:00');

            // 3. BDE Daily Performance Report - 03:00 PM
            // Sends pending/taken/delay stats to active BDEs.
            $schedule->command('report:bde-daily')
                     ->at('15:00');

        })->days([1, 2, 3, 4, 5, 6]); // Applies Mon-Sat filter to the whole group


        // ---------------------------------------------------------------------
        // OTHER TASKS
        // ---------------------------------------------------------------------
        
        // Auto-Logout at 8:00 PM Daily (If you want to enable it)
        // $schedule->command('users:logout')->dailyAt('20:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}