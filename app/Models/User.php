<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_no',
        'password',
        'image',
        'skills',
        'date_of_joining',
        'status',
        'is_active',
        'role_id',
        'department_id',
        'company_name',
        'salary',
        'company_gst',
        'address',
        'city',
        'mail_status',
        'mail_date',
        'client_status',
        'client_category_id'    ,
        'pan_no',
        'aadhar_no',
        'date_of_birth',
        'offer_letter',
        'offer_letter_status',
        'verification',
        'verified_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // protected static function boot()
    // {

    //     parent::boot();

    //     static::addGlobalScope('role_id', function ($query) {
    //         $query->where('role_id', '!=', '0');
    //     });
    // }

    // public function role()
    // {
    //     return $this->hasOne(Roles::class, 'id', 'role_id');
    // }

    public function taskassign()
    {
        return $this->hasMany(Tasks::class);
    }


    public function invoices()
    {
        return $this->HasMany(Invoices::class, 'id', 'client_id');
    }


    public function logs()
    {
        return $this->hasMany(Logs::class, 'id', 'user_id');
    }

    public function getImageAttribute($value)
    {
        return ($value) ? asset('profile/' . $value) : NULL;
    }

    public function project()
    {
        return $this->HasMany(Projects::class, 'client_id', 'id');
    }

    public function projects() {
        return $this->belongsToMany(Projects::class, 'project_user', 'user_id', 'project_id')
                    ->withPivot('assigned_user_id') // Include pivot columns you want to access
                    ->withTimestamps();
    }

    public function clients()
    {
        return $this->HasMany(Projects::class, 'client_id', 'id');
    }

    // public function tasks()
    // {
    //     return $this->belongsToMany(Tasks::class, 'user_task');
    // }

    public function tasks()
    {
        return $this->belongsToMany(Tasks::class, 'user_task', 'user_id', 'task_id')->where('user_task.deleted_at', NULL);
    }


    // public function scopeRole($query, $roleId)
    // {
    //     return $query->where('role_id', $roleId);
    // }


    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    // }

    // public function hasRole($roles)
    // {
    //     return $this->roles()->where('name', $roles)->first() != null;
    // }

    public function service(){
        return $this->HasMany(Work::class, 'client_id', 'id');
    }
    public function services(){
        return $this->hasMany(Work::class, 'client_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'client_category','category_id');
    }

    public function prposal(){
        return $this->hasOne(Invoice::class,'client_id','id');
    }

    public function totalAmount(){
        return $this->hasOne(TotalAmount::class, 'client_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lead(){
        return $this->hasMany(lead::class,'assigned_user_id','id');
    }

    public function followup(){
        return $this->belongsTo(Followup::class, 'user_id', 'id');
    }
    
    public function invoice(){
        return $this->hasOne(Invoice::class, 'client_id', 'id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function account(){
        return $this->hasOne(Account::class,'user_id','id');
    }
    public function document(){
        return $this->hasOne(Document::class,'user_id','id');
    }

    public function LateReason()
    {
        return $this->hasMany(LateReason::class, 'user_id', 'id');
    }

    public function dailyReport(){
        return $this->hasMany(DailyReport::class, 'user_id', 'id');
    }
    

    public function leave(){
        return $this->hasMany(Leaves::class, 'user_id', 'id');
    }

    public function today_leave(){
        return $this->hasOne(Leaves::class, 'user_id', 'id')
        ->whereDate('created_at', Carbon::today());
    }

    // public function role(){
    //     return $this->belongsTo(Role::class, 'role_id', 'id');
    // }

    public function reportsTo() {
        return $this->hasOne(UserHierarchy::class, 'user_id');
    }

    public function teamMembers()
    {
        return $this->hasManyThrough(
            User::class,           
            UserHierarchy::class,  
            'reports_to',          
            'id',                   
            'id',                  
            'user_id'          
        );
    }

    public function api(){
       return $this->hasOne(Api::class, 'user_id');
    }

    public function late(){
        return $this->hasMany(LateReason::class, 'user_id', 'id');
    }

    public function today_late(){
        return $this->hasOne(LateReason::class, 'user_id', 'id')
        ->whereDate('created_at', Carbon::today());
    }
}
