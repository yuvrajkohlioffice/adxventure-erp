<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    use HasFactory;

    
    protected $table = "project_user";

    protected $fillable = [
        'id','project_id','user_id'
    ];
    

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
