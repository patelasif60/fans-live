<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHospitalityHospitalitySuite extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_hospitality_hospitality_suites';

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match_hospitality_id', 'hospitality_suite_id',
    ];
}
