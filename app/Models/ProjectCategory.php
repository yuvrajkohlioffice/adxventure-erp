<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    use HasFactory;

    protected $table = 'project_category'; // Ensure this matches your actual table name
    protected $fillable = ['id', 'name'];

    // Define the many-to-many relationship with Lead
    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'lead_project_category', 'project_category_id', 'lead_id');
    }

    // Define the one-to-many relationship with Projects
    public function project()
    {
        return $this->hasMany(Projects::class, 'project_category', 'id');
    }

    public function lead(){
        return $this->hasMany(lead::class, 'project_category', 'id');
    }

    public function categories()
{
    return $this->belongsToMany(Category::class, 'category_service');
}

public function templates()
{
    return $this->hasMany(Template::class);
}
}
