<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalityUnavailableSeat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hospitality_unavailable_seats';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;
}
