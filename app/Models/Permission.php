<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // Example of a custom property
    protected $customProperty;

    // Example of a custom method
    public function customMethod()
    {
        return 'This is a custom method';
    }

    // Example of modifying an existing method
    public function save(array $options = [])
    {
        // Perform custom logic before saving
        if ($this->isDirty('name')) {
            // Do something when the 'name' attribute is changed
        }

        // Call the parent save method
        return parent::save($options);
    }

    // Example of adding an accessor
    public function getFormattedNameAttribute()
    {
        return ucfirst($this->name);
    }
}