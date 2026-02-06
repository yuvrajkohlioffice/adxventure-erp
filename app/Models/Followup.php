<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add this line

class Followup extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes here

    protected $table = 'follow_up';
    public $incrementing = false;

    protected $guarded = [];

    // This ensures that when you query Followup, it adds:
    // WHERE deleted_at IS NULL
    
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function relatedFollowups()
    {
        // This will now only show related followups that aren't soft-deleted
        return $this->hasMany(Followup::class, 'lead_id', 'lead_id');
    }
}