<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = "roles";

    protected $fillable = [
        'name','status'
    ];
    
    public function user()
    {
        return $this->HasMany(User::class,'role_id','id');
    }

    public function users()
    {
        return $this->belongToMany(User::class,'user_roles');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function hasPermission($permissions){
        return $this->permissions()->where('name',$permissions)->first() != null;
    }
}

