<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scorers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
