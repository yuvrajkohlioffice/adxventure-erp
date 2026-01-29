<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Email extends Model
{
    use HasFactory;

    protected $table = "emails";

    protected $fillable = [
        'lead_id','user_id','template_id','title','message','sender_id'
    ];

    public function template(){
        return $this->belongsTo(Template::class,'template_id','id');
    }
}
