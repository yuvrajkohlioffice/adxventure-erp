<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory, SoftDeletes;
     
    protected $table = "work";

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'work_name', 'work_quality', 'work_price', 'work_type','invoice_id','client_id','project_id','total_amount','lead_id','currency'
    ];
    
    public function invoice()
    {
        return $this->HasMany('App\Invoice','id','invoice_id');
    }

    public function lead(){
        return $this->hasMany(Lead::class,'lead_id','id');
    }

}
