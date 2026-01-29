<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalAmount extends Model
{
    use HasFactory;
     
    protected $table = "total_amount";

    protected $fillable = [
        'lead_id','client_id','total_amount','pay','balance','gst','invoice_id','amount','discount',
    ];
}
    