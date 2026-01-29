<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Logs;

class LogoutUsers extends Command
{
    protected $signature = 'users:autologoutall';
    protected $description = 'Automatically logs out all users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Retrieve all active sessions
        $sessions = DB::table('sessions')->get();

        // Loop through each session and log the user out
        foreach ($sessions as $session) {
            // Check if the payload field exists
            if (!property_exists($session, 'payload')) {
                $this->error('Session payload not found for session ID: ' . $session->id);
                continue;
            }

            // Get the user ID from the session payload
            $data = unserialize(base64_decode($session->payload));

            if (isset($data['login'])) {
                $userId = $data['login'];

                // Log the event for each user
                Logs::LoginLogsCreate($userId, 2, 'Login session ended by cron job');

                // Delete the session to log out the user
                DB::table('sessions')->where('id', $session->id)->delete();
            }
        }

        $this->info('All users have been logged out.');
    }
}
