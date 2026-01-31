<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'lead';

    protected $guarded = [];

    // Optimize JSON handling at the database level
    protected $casts = [
        'project_category' => 'array',
        'created_at' => 'datetime',
        'next_date' => 'datetime',
    ];

    // --- Scopes for Performance ---
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // --- Relationships ---

    // Many-to-Many (if pivot exists)
    public function projectCategories()
    {
        return $this->belongsToMany(ProjectCategory::class, 'lead_project_category', 'lead_id', 'project_category_id');
    }

    public function Followup()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'id');
    }

    // Optimization: Load latest followup efficiently
    public function latestFollowup()
    {
        return $this->hasOne(Followup::class, 'lead_id', 'id')->latestOfMany();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'client_category', 'category_id')->withDefault(['name' => 'N/A']);
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
        return $this->belongsTo(User::class, 'client_id', 'id')->withDefault(['name' => 'Unknown']);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'lead_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => 'Unassigned']);
    }

    // Optimization: Fixed naming convention for clarity, kept original name for backward compatibility
    public function AssignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id')->withDefault(['name' => 'System']);
    }

    public function assignd_user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(ProjectInvoice::class, 'id', 'lead_id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country', 'id')->withDefault(['nicename' => 'Unknown']);
    }
}