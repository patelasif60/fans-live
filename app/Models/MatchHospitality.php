<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHospitality extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_hospitalities';

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

    /**
     * Get a match hospitality hospitality suites.
     */
    public function hospitalitySuites()
    {
        return $this->belongsToMany(\App\Models\HospitalitySuite::class, 'match_hospitality_hospitality_suites', 'match_hospitality_id', 'hospitality_suite_id');
    }
}
