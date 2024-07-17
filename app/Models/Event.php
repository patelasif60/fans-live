<?php

namespace App\Models;

use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookedEvent;

/**
 * Event model class for table request.
 */
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'club_id', 'title', 'description', 'image', 'image_file_name', 'status', 'location', 'date_time', 'rewards_percentage_override', 'price', 'vat_rate', 'number_of_tickets', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get event membership package.
     */
    public function eventMembershipPackageAvailability()
    {
        return $this->hasMany(EventMembershipPackageAvailability::class, 'event_id');
    }
     /**
     * Get event membership package.
     */
    public function eventTransaction()
    {
        return $this->hasMany(EventTransaction::class, 'event_id');
    }

    /**
     * Get event membership package.
     */
    public function eventMembershipPackageAccess()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        return $this->eventMembershipPackageAccessByConsumer($consumer);
    }

    /**
     * Get event membership package by consumer
     *
     * @param $consumer
     *
     * return boolean
     */
    public function eventMembershipPackageAccessByConsumer($consumer)
    {
        $isEventAccessible = false;
        $eventMembershipPackages = $this->eventMembershipPackageAvailability->pluck('membership_package_id')->toArray();
        if(in_array(config("fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID"), $eventMembershipPackages)) {
            return true;
        }
        $consumerPackage = $consumer->getActiveMembershipPackage();
        if($consumerPackage) {
            $consumerPackageId = $consumerPackage->membership_package_id;

            if(in_array($consumerPackageId, $eventMembershipPackages)) {
                $isEventAccessible = true;
            }
        }
        return $isEventAccessible;
    }

    /**
     * Get membership package.
     */
    public function membershipPackage()
    {
        return $this->belongsToMany(\App\Models\MembershipPackage::class, 'event_membership_package_availability', 'event_id', 'membership_package_id');
    }

    /**
     * Get event tickets.
     */
    public function getEventTickets($event)
    {
       return BookedEvent::whereIn('event_transaction_id',$event->eventTransaction->pluck('id'))->max('seat');
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }

    /**
     * Get vat rate.
     */
    public function getVatRateAttribute()
    {
        return formatNumber($this->attributes['vat_rate']);
    }
}
