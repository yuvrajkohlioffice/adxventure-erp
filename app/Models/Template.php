<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    
    protected $table = "template";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id', 'title', 'message', 'type','category','project_id'
    ];
        
    
}