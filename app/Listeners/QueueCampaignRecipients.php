<?php
namespace App\Listeners;
use Illuminate\Support\Facades\Bus;
use App\Events\CampaignStarted;
use App\Jobs\SendCampaignMessageJob;
use App\Models\CampaignRecipient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QueueCampaignRecipients implements ShouldQueue
{
        use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CampaignStarted $event): void
    {
        $campaign = $event->campaign;

        $recipients = CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'pending')
            ->orderBy('id')
            ->get();

        foreach ($recipients as $index => $recipient) {
            SendCampaignMessageJob::dispatch(
                $recipient,
                $index + 1 // pass counter
            )->onQueue('campaigns');
        }
        
        Bus::batch($jobs)
        ->then(function () use ($campaign) {
            // ✅ ALL JOBS COMPLETED SUCCESSFULLY
            $campaign->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        })
        ->catch(function ($batch, $e) use ($campaign) {
            // ❌ SOME JOB FAILED
            $campaign->update([
                'status' => 'completed', // still completed
            ]);
        })
        ->dispatch();
    }
}
