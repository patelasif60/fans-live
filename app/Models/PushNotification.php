<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasOwnerRelationShip;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'push_notifications';

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

    /**
     * The membership packages that belong to the video.
     */
    public function membershippackages()
    {
        return $this->belongsToMany(\App\Models\MembershipPackage::class, 'push_notification_membership_package', 'push_notification_id', 'membership_package_id');
    }
}
