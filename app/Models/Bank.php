<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = "bank";
    public $incrementing = false;

    protected $fillable = [
        'id', 'bank_name', 'holder_name','account_no','ifsc','gst','status','scanner','user_id','verify'
    ];
   
}
