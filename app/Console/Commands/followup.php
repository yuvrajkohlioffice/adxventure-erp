// <?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Auth;
// use App\Models\Logs; // Import your Logs model

// class followup extends Command
// {
//     protected $signature = 'users:logout';
//     protected $description = 'Log out all users and perform additional actions';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         $users = \App\Models\User::all(); // Fetch all users, or filter as needed
//         foreach ($users as $user) {
//             // Log the logout event
//             Logs::LoginLogsCreate($user->id, 2, 'Login session ended');

//             // Log out the user (note: this is usually for web requests; adapt as needed)
//             Auth::guard('web')->logout();
//         }

//         $this->info('All users have been logged out.');
//     }
// }
