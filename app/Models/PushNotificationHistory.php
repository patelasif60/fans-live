<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotificationHistory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'push_notification_history';

    /**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];
}
