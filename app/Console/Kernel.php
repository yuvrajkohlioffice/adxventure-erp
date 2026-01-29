<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\CronController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $cron = new CronController();

        // Run login reminder daily at 9:45 AM (except Sunday)
        $schedule->call(function () use ($cron) {
            $cron->login_reminder();
        })->days([1, 2, 3, 4, 5, 6])->at('09:45');

        // Run login employee daily at 10:00 AM (except Sunday)
        $schedule->call(function () use ($cron) {
            $cron->admin_login_mail();
        })->days([1, 2, 3, 4, 5, 6])->at('10:00');

        // Schedule the command to run daily at 8 PM
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
    
    protected $commands = [
        \App\Console\Commands\LogoutUsers::class,
    ];
}