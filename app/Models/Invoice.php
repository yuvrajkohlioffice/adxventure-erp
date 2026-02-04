<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "Invoices";
    protected $dates = ['deleted_at'];

    protected $guarded  =[];
    
    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id','id');
    }

    public function client(){
        return $this->BelongsTo(User::class,'client_id','id');
    }

    public function works()
    {
        return $this->HasMany(Work::class,'invoice_id','id' );
    }

    // public function client()
    // {
    //     return $this->belongsTo(User::class, 'client_id');
    // }

    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    public function payment(){
        return $this->HasMany(Payment::class,'invoice_id','id');
    }

    public function Bank(){
        return $this->hasOne(Bank::class,'id','bank');

    }

    public function Followup(){
        return $this->HasMany(Followup::class,'invoice_id','id');
    }

    public function proposal(){
        return $this->HasMany(Proposal::class,'invoice_id','id');
    }

    public function receipts()
    {
        return $this->belongsToMany(Receipt::class, 'invoice_receipt', 'invoice_id', 'receipt_id');
    } 

}
