<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cms';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user detail.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Check for club access.
     */
    public function hasClubAccess($clubId)
    {
        return $this->club_id == $clubId ? true : false;
    }
}
