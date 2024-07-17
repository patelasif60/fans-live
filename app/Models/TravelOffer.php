<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TravelOffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'travel_offers';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];
}
