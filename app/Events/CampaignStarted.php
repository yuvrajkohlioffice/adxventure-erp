<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignStarted 
{
    use Dispatchable, SerializesModels; 
    public $campaign;

    public function __construct(Campaign $campaign)
    {
        // dd('here');
        $this->campaign = $campaign;
    }
}
