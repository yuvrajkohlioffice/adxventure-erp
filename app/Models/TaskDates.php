<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDates extends Model
{
    use HasFactory;

    protected $table = "task_dates";

    protected $fillable = [
        'task_id','date'
    ];

    public function task(){
        return $this->BelongsTo(Tasks::class,'task_id','id');
    }

    public function tasktiming(){
        return $this->HasOne(TaskTiming::class,'task_date_id','id');
    }

    public function report(){
        return $this->HasOne(Reports::class,'task_id','task_id');
    }

}