<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Followup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBdeDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:bde-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 3 PM status report to all BDEs (plus HR/Admin) using sendMail helper';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // ---------------------------------------------------------
        // 1. CONFIGURATION: CC EMAILS
        // ---------------------------------------------------------
        $ccEmails = [   
            'yuvrajkohli8090ylt@gmail.com',
            'hr@adxventure.com',
            'suyalvikas@gmail.com'
        ];

        // ---------------------------------------------------------
        // 2. GET ACTIVE BDEs
        // ---------------------------------------------------------
        $bdes = User::where('role_id', 8)->where('is_active', 1)->get();

        $this->info("Found " . $bdes->count() . " active BDEs. Starting processing...");
        Log::info("BDE Report Command Started: Found " . $bdes->count() . " active BDEs.");

        foreach ($bdes as $user) {
            
            // 3. Check if they have taken ANY followup today
            $workedLeadIds = Followup::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->pluck('lead_id')
                ->toArray();
            
            // Optional: Skip if no work done (Uncomment if needed)
            if (count($workedLeadIds) == 0) {
                 continue;
            }

            // ---------------------------------------------------------
            // 4. CALCULATE REAL DATA
            // ---------------------------------------------------------

            // A. Taken Today
            $countTaken = count(array_unique($workedLeadIds));

            // B. Pending Today
            $countPending = Followup::whereDate('next_date', $today)
                ->where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
                })
                ->whereNotIn('lead_id', $workedLeadIds)
                ->distinct('lead_id')
                ->count();

            // C. Total Delayed
            $countDelay = Followup::whereHas('lead', function ($q) use ($user) {
                $q->where(function ($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->orWhere('assigned_by', $user->id)
                        ->orWhere('assigned_user_id', $user->id);
                });

                $q->whereDoesntHave('lastFollowup', function ($sq) {
                    $sq->whereIn('reason', [
                        'Not interested',
                        'Wrong Information',
                        'Work with other company'
                    ]);
                });
            })
            ->where(function ($q) {
                $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
            })
            ->where('delay', '>=', 1)
            ->whereNotIn('lead_id', $workedLeadIds)
            ->distinct('lead_id')
            ->count('lead_id');

            // ---------------------------------------------------------
            // 5. PREPARE EMAIL CONTENT
            // ---------------------------------------------------------
            
            $subject = "3 PM Work Status - " . $user->name . " (" . now()->format('d M') . ")";
            $header = "Daily Performance Snapshot";
            $dateFormatted = now()->format('d M Y');

            // Dynamic Colors
            $pendingColor = $countPending > 0 ? '#ffc107' : '#28a745'; 
            $delayColor   = $countDelay > 0 ? '#dc3545' : '#28a745';   

            $message = "
                <div style='font-family: Arial, sans-serif; padding: 10px; color: #333;'>
                    <h3 style='border-bottom: 2px solid #0d6efd; padding-bottom: 10px; color: #0d6efd;'>Daily Performance Snapshot</h3>
                    
                    <p>Dear <strong>{$user->name}</strong>,</p>
                    <p>Here is your mid-day status report for <strong>{$dateFormatted}</strong>.</p>
                    
                    <table style='width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px; background: #fff; border: 1px solid #ddd;'>
                        <thead>
                            <tr style='background-color: #f8f9fa; text-align: left;'>
                                <th style='padding: 10px; border: 1px solid #ddd;'>Metric</th>
                                <th style='padding: 10px; border: 1px solid #ddd;'>Count</th>
                                <th style='padding: 10px; border: 1px solid #ddd;'>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Taken Today</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; font-size: 16px;'><strong>{$countTaken}</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; color: #28a745; font-weight: bold;'>Active</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Pending Today</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; font-size: 16px;'><strong>{$countPending}</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; color: {$pendingColor}; font-weight: bold;'>
                                    " . ($countPending > 0 ? 'Needs Attention' : 'All Clear') . "
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd;'><strong>Total Delayed</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; font-size: 16px;'><strong>{$countDelay}</strong></td>
                                <td style='padding: 10px; border: 1px solid #ddd; color: {$delayColor}; font-weight: bold;'>
                                    " . ($countDelay > 0 ? 'Critical' : 'Excellent') . "
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p><strong>Action Required:</strong></p>
                    <ul>
                        <li>Please clear your <strong>{$countPending} pending calls</strong> before EOD.</li>
                        <li>Try to reduce your <strong>{$countDelay} delayed leads</strong>.</li>
                    </ul>
                    <br>
                    <p style='font-size: 12px; color: #777;'>This is an automated report.</p>
                </div>
            ";

            // ---------------------------------------------------------
            // 6. SEND EMAIL USING HELPER
            // ---------------------------------------------------------
            
            // Combine BDE email with CC emails into one array
            // The helper function iterates this array and adds them all as recipients
            $recipients = array_merge([$user->email], $ccEmails);

            try {
                // Call the helper function: sendMail($to, $subject, $header, $message, $footer)
                $status = sendMail($recipients, $subject, $header, $message, null);

                if ($status) {
                    $logMsg = "Report sent to [{$user->name}] and CCs.";
                    $this->info("✔ " . $logMsg);
                } else {
                    $this->error("✘ Helper returned false for [{$user->name}]");
                }

            } catch (\Exception $e) {
                $errorMsg = "FAILED to send report to [{$user->name}]. Error: {$e->getMessage()}";
                $this->error("✘ " . $errorMsg);
                Log::error($errorMsg);
            }
        }

        $this->info('All BDE Reports processed.');
        Log::info('BDE Report Command Finished.');
    }
}