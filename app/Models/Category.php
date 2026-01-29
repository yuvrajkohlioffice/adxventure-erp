<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "category";
    protected $primaryKey = 'category_id';
    public $incrementing = false;

    protected $fillable = [
        'category_id', 'name',
    ];

    public function project()
    {
        return $this->hasMany(Projects::class, 'category', 'category_id');
    }

    public function lead(){
        return $this->hasMany(lead::class, 'client_category', 'category_id');
    }
}
