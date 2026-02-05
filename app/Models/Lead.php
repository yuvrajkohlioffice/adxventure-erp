<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     * Note: Laravel expects 'leads', so we define 'lead' explicitly.
     */
    protected $table = 'lead';

    // ============================
    // 1. FILLABLE & CASTS
    // ============================

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'status',
        'lead_status',
        'client_category',
        'assigned_user_id',
        'assigned_by',
        'user_id',
        'project_category',
        'quotation',
        'quotation_date',
        'country',
        'description' // Added common field just in case
    ];

    protected $casts = [
        'project_category' => 'array',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'quotation_date'   => 'datetime',
        'assigned_date'    => 'datetime',
        'status'           => 'integer',
    ];


    // 2. Standardized Relationships (CamelCase)

    public function category()
    {
        return $this->belongsTo(Category::class, 'client_category', 'category_id'); // Assuming category_id was typo
    }
    public function totalAmount()
    {
        return $this->hasOne(TotalAmount::class, 'lead_id', 'id');
    }
    public function Followup()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'id');
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
    public function projectCategories()
    {
        return $this->belongsToMany(ProjectCategory::class, 'lead_project_category', 'lead_id', 'project_category_id');
    }

    // Normalized: 'followups' (Plural)
    public function followups()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'id');
    }

    // Performance: Relationship to get ONLY the latest followup
    public function latestFollowup()
    {
        return $this->hasOne(Followup::class)->latestOfMany();
    }

    public function invoice()
    {
        return $this->hasOne(ProjectInvoice::class, 'lead_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

  

    // 3. Performance Scopes (Moves Logic out of Controller)

    /**
     * Apply all standard filters
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search']['value'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        });

        $query->when($filters['country'] ?? null, fn($q, $val) => $q->where('country', $val));

        $query->when($filters['lead_day'] ?? null, function ($q, $val) use ($filters) {
            $this->applyDateScope($q, $val, 'created_at', $filters);
        });

        $query->when($filters['bde'] ?? null, fn($q, $val) => $q->where('assigned_user_id', $val));

        // Logic for "Button Filters" (The massive switch statement)
        if (!empty($filters['lead_type'])) {
            $this->applyStatusScope($query, $filters['lead_type']);
        }
    }

    private function applyDateScope($query, $range, $column, $filters)
    {
        match ($range) {
            'today'   => $query->whereDate($column, Carbon::today()),
            'month'   => $query->whereMonth($column, Carbon::now()->month),
            'year'    => $query->whereYear($column, Carbon::now()->year),
            'custome' => $query->when($filters['from_date'] ?? null, function ($q) use ($filters) {
                $q->whereBetween($column, [
                    Carbon::parse($filters['from_date']),
                    Carbon::parse($filters['to_date'])->endOfDay()
                ]);
            }),
            default => null
        };
    }

    private function applyStatusScope($query, $type)
    {
        // Optimized Filter Logic
        switch ($type) {
            case 'fresh_lead':
                $query->doesntHave('followups');
                break;
            case 'today_followup':
                $query->whereHas('followups', fn($q) => $q->whereDate('next_date', Carbon::today()));
                break;
            case 'delay':
                $query->whereHas(
                    'followups',
                    fn($q) =>
                    $q->where('delay', 1)->orWhere('is_completed', '!=', 1)
                );
                break;
            case 'cold_clients':
                $query->whereHas(
                    'followups',
                    fn($q) =>
                    $q->whereIn('reason', ['call back later', 'Not pickup'])
                );
                break;
                // ... Add remaining cases here ...
        }
    }
}
