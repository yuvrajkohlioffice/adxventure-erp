<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    use HasFactory;

    protected $table = "leaves";

    protected $fillable = [
        'user_id','from_date','to_date','status','request','remark','approved_hr_id'
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}