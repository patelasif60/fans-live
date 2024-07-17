<?php

namespace App\Models;

use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class HospitalityDietaryOptions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hospitality_suite_dietary_options';

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
