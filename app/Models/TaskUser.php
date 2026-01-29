<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "user_task";

    protected $fillable = [
        'user_id','task_id','status'
    ];
    public function users(){
        
        return $this->belongsTo(User::class,'user_id','id');
    }
    
}
