<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedHospitalityTransactionDietaryOptions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'booked_hospitality_suite_transaction_dietary_options';

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
