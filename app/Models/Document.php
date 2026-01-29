<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "documents";

    protected $fillable = [
        'user_id','aadhar_front_img','aadhar_back_img','pan_img','account_img',
    ];

}
