<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'standings';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the club detail.
     */
    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class);
    }
}
