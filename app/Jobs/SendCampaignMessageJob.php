<?php

namespace App\Jobs;

use App\Models\CampaignRecipient;
use App\Services\RecipientValidator;
use App\Traits\SendsEmail;
use App\Traits\SendsWhatsApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use SendsEmail, SendsWhatsApp;

    protected CampaignRecipient $recipient;
    protected int $counter;

    public function __construct(CampaignRecipient $recipient , int $counter)
    {
        $this->recipient = $recipient;
        $this->counter   = $counter;
    }

    public function handle()
    {

        $sleep = rand(10, 15); 
        if ($this->counter % 3 === 0) {
            $sleep += 60; 
        }
        sleep($sleep);

        $r = $this->recipient;

        // Prevent duplicate processing
        $updated = CampaignRecipient::where('id', $r->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'processing',
                'processed_at' => now()
            ]);

        if (!$updated) {
            return;
        }

        $campaign = $r->campaign;

        // Validation
        if ($r->channel === 'email' && !RecipientValidator::email($r->email)) {
            return $this->fail('Invalid email');
        }

        if ($r->channel === 'whatsapp' && !RecipientValidator::phone($r->phone)) {
            return $this->fail('Invalid phone');
        }

        try {
            if ($r->channel === 'email') {
                $this->sendEmail($r->email, $campaign->message);
            } else {
                $this->sendWhatsApp($r->phone, $campaign->message);
            }

            $r->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

        } catch (\Throwable $e) {
            $this->fail($e->getMessage());
        }
    }

    private function fail(string $reason): void
    {
        $this->recipient->update([
            'status' => 'failed',
            'failed_reason' => $reason
        ]);
    }
}
