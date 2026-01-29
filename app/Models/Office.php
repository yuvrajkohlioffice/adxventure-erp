<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = "offices";

    protected $fillable = [
        'name','email','phone','city','state','country', 'tax_no','zip_code','address'
    ];
    
}
