<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventNotification extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'event_notifications';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_notified' => 'boolean',
	];

	/**
     * Get event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get consumer.
     */
    public function consumer()
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }
}
