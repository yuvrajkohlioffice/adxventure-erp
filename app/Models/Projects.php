<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "projects";

    protected $fillable = [
        'user_id','client_id','logo','name','website','jd','reason','status','manager','team_leader','company_name','social_media','gst_no','category','project_category','invoice_date','contact_person_name','contact_person_mobile','bank'
    ];

    public function getLogoAttribute($value){
        return asset('projects/'.$value);
    }

    public function task(){
        return $this->hasMany(Tasks::class,'project_id','id');
    }
    
    public function projectManager(){
        return $this->HasOne(User::class,'id','manager');
    }
    
    public function teamLeader(){
        return $this->HasOne(User::class,'id','team_leader');
    }

    public function client(){
        return $this->belongsTo(User::class,'client_id','id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
                    ->withPivot('assigned_user_id') // Include pivot columns you want to access
                    ->withTimestamps();
    }   

    

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function getWebsiteAttribute($value){
        if(!$value){
            return NULL;
        }
        
        if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
            // If not, add "http://"
            return "http://" . $value;
        }
    
        return $value;
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','category');
    }
    public function projectCategory(){
        return $this->belongsTo(ProjectCategory::class,'project_category','id');
    }

    public function bank(){
        return $this->belongsTo(Bank::class,'bank','id');
    }

    public function invoice(){
        return $this->hasMany(ProjectInvoice::class,'project_id','id');
    }

    public function work(){
        return $this->hasMany(Work::class,'project_id','id');
    }

    public function Followup(){
        return $this->hasMany(Followup::class,'project_id','id');
    }

    public function projectUser(){
        return $this->hasMany(Followup::class,'project_id','id');
    }
}

