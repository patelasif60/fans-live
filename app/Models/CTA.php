<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CTA extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ctas';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
