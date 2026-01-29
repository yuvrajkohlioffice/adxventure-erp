<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "tasks";

    protected $guarded = [];

    public function getLogoAttribute($value){
        return asset('images/'.$value);
    }

    public function project(){
        return $this->belongsTo(Projects::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reports(){
        return $this->HasMany(Reports::class,'task_id','id');
    }

    public function report(){
        return $this->HasOne(Reports::class,'task_id','id')->orderBy('id','desc');
    }

    public function tasktime(){
        return $this->HasOne(TaskTiming::class,'task_id','id')->orderBy('id','desc');
    }
    
    public function taskdates(){
        return $this->HasMany(TaskDates::class,'task_id','id');
    }
    
    public function dailyTask(){
        return $this->HasOne(TaskTiming::class,'task_id','id')->whereDate('created_at',date('Y-m-d'))->where('task_date_id','0');
    }

    public function taskdatestiming(){
        return $this->HasOne(TaskDates::class,'task_id','id');
    }

    public function todaydate(){
        return $this->HasOne(TaskDates::class,'task_id','id')->where('task_dates.date',date("Y-m-d"));
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_task', 'task_id', 'user_id')
                    ->wherePivot('status', 1)
                    ->whereNull('user_task.deleted_at');
    }
    
    public function organiser(){
        return $this->HasOne(User::class,'id','task_organiser');
    }

}
