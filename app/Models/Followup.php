<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    protected $table = 'follow_up';
    public $incrementing = false;

    protected $guarded = [];

    public function lead()
    {
        return $this->belongsTo(lead::class, 'lead_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function relatedFollowups()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'lead_id');
    }

    public function scopeForUser($query, $user)
    {
        if ($user->hasRole(['BDE', 'Business Development Intern'])) {
            return $query->where('user_id', $user->id);
        }
        return $query;
    }
}
