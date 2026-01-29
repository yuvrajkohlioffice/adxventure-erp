<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTiming extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "task_timing";

    protected $fillable = [
        'user_id','task_id','start_date','end_date','task_date_id','paused_time','restart_time'
    ];

    public function task(){
        return $this->BelongsTo(Tasks::class);
    }

}
