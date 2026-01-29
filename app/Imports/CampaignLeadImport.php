<?php

namespace App\Imports;

use App\Models\CampaignRecipient;
use App\Services\RecipientValidator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class CampaignLeadImport implements ToCollection, WithHeadingRow
{
    protected $campaignId;
    protected $channel;

    public function __construct($campaignId, $channel)
    {
        $this->campaignId = $campaignId;
        $this->channel = $channel;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $email = $row['email'] ?? null;
            $phone = $row['phone'] ?? null;

            // 🔴 Empty check
            if (!$email && !$phone) {
                $this->failed(null, null, 'Email and phone both empty');
                continue;
            }

            // 🔴 Email validation
            if ($this->channel === 'email') {
                if (!RecipientValidator::email($email)) {
                    $this->failed($email, null, 'Invalid email');
                    continue;
                }
            }

            // 🔴 Phone validation
            if ($this->channel === 'whatsapp') {
                // if (!RecipientValidator::phone($phone)) {
                //     $this->failed(null, $phone, 'Invalid phone');
                //     continue;
                // }
                $phone = RecipientValidator::normalizePhone($phone);
                if (!RecipientValidator::phone($phone)) {
                    $status = 'failed';
                    $reason = 'Invalid phone number';
                }
            }

            // 🔴 Duplicate check
            $exists = CampaignRecipient::where('campaign_id', $this->campaignId)
                ->where(function($q) use ($email, $phone) {
                    if ($email) $q->orWhere('email', $email);
                    if ($phone) $q->orWhere('phone', $phone);
                })
                ->exists();

            if ($exists) {
                $this->failed($email, $phone, 'Duplicate lead');
                continue;
            }

            // ✅ Insert valid lead
            CampaignRecipient::create([
                'campaign_id' => $this->campaignId,
                'email' => $email,
                'phone' => $phone,
                'channel' => $this->channel,
                'status' => 'pending'
            ]);
        }
    }

    private function failed($email, $phone, $reason)
    {
        CampaignRecipient::create([
            'campaign_id' => $this->campaignId,
            'email' => $email,
            'phone' => $phone,
            'channel' => $this->channel,
            'status' => 'failed',
            'failed_reason' => $reason
        ]);
    }
}
