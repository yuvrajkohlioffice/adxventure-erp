<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
     
    protected $table = "payments";

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function invoice()
    {
        return $this->hasOne(ProjectInvoice::class,'id','invoice_id');
    }

    public function lead(){
        return $this->hasOne(lead::class,'id','lead_id');
    }
    
    public function client(){
        return $this->hasOne(User::class,'id','client_id');
    }
    
    public function  service(){
        return $this->hasMany(Work::class,'invoice_id','invoice_id');
    }

}
