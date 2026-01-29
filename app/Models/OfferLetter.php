<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    use HasFactory;

    protected $table = "offer_letters";

    protected $guarded = [];
    
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


}