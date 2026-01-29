<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "account_details";

    protected $fillable = [
        'user_id','account_holder_name','bank_name','ifsc','account_no',
    ];

}
