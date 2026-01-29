<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;

class UserHierarchy extends Model
{

    protected $guarded = [];
    protected $table = 'user_hierarchy';

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager() {
        return $this->belongsTo(User::class, 'reports_to');
    }
}