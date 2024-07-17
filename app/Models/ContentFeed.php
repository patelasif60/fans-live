<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ContentFeed extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'content_feeds';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
