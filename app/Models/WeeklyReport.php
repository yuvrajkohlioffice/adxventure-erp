<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "weekly_reports";
    protected $dates = ['deleted_at'];

    protected $guarded  =[];

    public function project(){
        return $this->belongsTo(Projects::class,'project_id','id');
    }

}
