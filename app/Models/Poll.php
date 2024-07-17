<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'club_id', 'title', 'question', 'answers', 'status', 'publication_date', 'closing_date', 'display_results_date', 'associated_match', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'polls';

    /**
     * get publication date in format.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['option_count'];

    /**
     * Get the options for a poll.
     */
    public function pollOptions()
    {
        return $this->hasMany(\App\Models\PollOption::class, 'poll_id');
    }

    /**
     * Get the total count of poll options.
     */
    public function getOptionCountAttribute()
    {
        return $this->pollOptions()->get()->pluck('count')->sum();
    }
}
