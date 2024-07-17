<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'poll_options';

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
     * Get a poll.
     */
    public function poll()
    {
        return $this->belongsTo(\App\Models\Poll::class);
    }
}
