<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MatchHospitalityMembershipPackage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_hospitality_membership_package';

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
