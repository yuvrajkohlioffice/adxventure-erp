<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class CustomRole extends SpatieRole
{
    // You can add custom properties or methods here

    // Example: Adding a custom method
    public function customMethod()
    {
        return 'This is a custom method';
    }

    // Example: Adding an accessor
    public function getCustomAttribute()
    {
        return 'Custom Attribute Value';
    }
}
