<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'lead'; // Ensure this matches your actual table name

    protected $guarded = [];

    // Define the many-to-many relationship with ProjectCategory
    public function projectCategories()
    {
        return $this->belongsToMany(ProjectCategory::class, 'lead_project_category', 'lead_id', 'project_category_id');
    }

    public function Followup()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'client_category', 'category_id');
    }

    public function service()
    {
        return $this->belongsTo(Work::class, 'id', 'lead_id');
    }
    public function services()
    {
        return $this->hasMany(Work::class, 'lead_id', 'id');
    }

    public function prposal()
    {
        return $this->hasOne(Invoice::class, 'lead_id', 'id');
    }

    public function totalAmount()
    {
        return $this->hasOne(TotalAmount::class, 'lead_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'lead_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function AssignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    public function assignd_user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(ProjectInvoice::class, 'id', 'lead_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function prposals()
    {
        return $this->belongsTo(Proposal::class, 'id', 'lead_id');
    }

    public function lastFollowup()
    {
        return $this->hasOne(Followup::class)->latestOfMany('id');
    }
    public function scopeForUser($query, $user)
    {
        // If user is BDE/Intern, restrict data. Otherwise, show all.
        if ($user->hasRole(['BDE', 'Business Development Intern'])) {
            return $query->where('assigned_user_id', $user->id);
        }
        return $query;
    }

    public function scopeCreatedToday($query)
    {
        return $query->whereDate('created_at', now());
    }
}
