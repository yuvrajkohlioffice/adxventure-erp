<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medias extends Model
{
    use HasFactory;

    protected $table = "medias";

    protected $fillable = [
        'mediable_id','mediable_type','filename'
    ];
    
    public function mediable()
    {
        return $this->morphTo();
    }

}
