<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reports extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "task_report";

    protected $fillable = [
        'user_id','percent','submit_date','remark','task_id','status','task_date_id','reject_remark','url'
    ];

    public function task(){
        return $this->BelongsTo(Tasks::class);
    }

    public function media()
    {
        return $this->morphMany(Medias::class, 'mediable');
    }
   
}
