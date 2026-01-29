<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invowork extends Model
{
    protected $fillable = [
        'client_id', 'invoice_id', 'work_id',
    ];
    
    public function work()
    {
        return $this->belongsTo('App\Work')->withTrashed ();
    }
}