<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

Class CampaignRecipient extends Model 
{
    use HasFactory;
    protected $guarded = [];

    public function Campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

}