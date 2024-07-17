<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class Video extends Model
{
    use HasOwnerRelationShip;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'videos';

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
        return $this->belongsToMany(\App\Models\MembershipPackage::class, 'video_membership_package', 'video_id', 'membership_package_id');
    }

    /**
     * Get video membership package.
     */
    public function videoMembershipPackageAccess()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        return $this->videoMembershipPackageAccessByConsumer($consumer);
    }

    /**
     * Get video membership package.
     */
    public function videoMembershipPackages()
    {
        return $this->hasMany(VideoMembershipPackage::class, 'video_id');
    }

    /**
     * Get video membership package by consumer
     *
     * @param $consumer
     *
     * return boolean
     */
    public function videoMembershipPackageAccessByConsumer($consumer)
    {
        $isVideoAccessible = false;
        $videoMembershipPackages = $this->videoMembershipPackages->pluck('membership_package_id')->toArray();
        if(in_array(config("fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID"), $videoMembershipPackages)) {
            return true;
        }
        $consumerPackage = $consumer->getActiveMembershipPackage();
        if($consumerPackage) {
            $consumerPackageId = $consumerPackage->membership_package_id;
            if(in_array($consumerPackageId, $videoMembershipPackages)) {
                $isVideoAccessible = true;
            }
        } else {
            $isVideoAccessible = true;
        }
        return $isVideoAccessible;
    }
}
