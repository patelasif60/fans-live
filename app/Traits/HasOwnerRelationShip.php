<?php

namespace App\Traits;

use App\Models\User;

trait HasOwnerRelationShip
{
    /**
     * Get the user who created the model.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Get the user who updated the model.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
}
