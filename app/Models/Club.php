<?php

namespace App\Models;

use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasOwnerRelationShip;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clubs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get a category details.
     */
    public function category()
    {
        return $this->belongsto(\App\Models\ClubCategory::class, 'club_category_id');
    }

    /**
     * The competitions that belong to the club.
     */
    public function competitions()
    {
        return $this->belongsToMany(\App\Models\Competition::class, 'competition_club');
    }

    /**
     * The stadium that belong to the club.
     */
    public function stadium()
    {
        return $this->hasOne(\App\Models\StadiumGeneralSetting::class, 'club_id');
    }

    /**
     * Get a primary competition for the club.
     */
    public function getPrimaryCompetition()
    {
        return $this->competitions()->where('is_primary', true)->first();
    }

    /**
     * The club_opening_time_settings that belong to the club.
     */
    public function clubOpeningTimeSettings()
    {
        return $this->hasOne(\App\Models\ClubOpeningTimeSetting::class, 'club_id');
    }

    /**
     * The club_text_settings that belong to the club.
     */
    public function clubTextSettings()
    {
        return $this->hasOne(\App\Models\ClubTextSetting::class, 'club_id');
    }

    /**
     * The club_loyalty_point_settings that belong to the club.
     */
    public function clubLoyaltyPointSettings()
    {
        return $this->hasOne(\App\Models\ClubLoyaltyPointSetting::class, 'club_id');
    }

    /**
     * The club_module_settings that belong to the club.
     */
    public function moduleSettings()
    {
        return $this->hasMany(\App\Models\ClubModuleSetting::class, 'club_id');
    }

    /**
     * The stadium blocks that belong to the club.
     */
    public function stadiumBlocks()
    {
        return $this->hasMany(\App\Models\StadiumBlock::class, 'club_id');
    }

    /**
     * The stadium entrances that belong to the club.
     */
    public function stadiumEntrances()
    {
        return $this->hasMany(\App\Models\StadiumEntrance::class, 'club_id');
    }

    /**
     * The stadium entrances that belong to the club.
     */
    public function clubBankDetail()
    {
        return $this->hasOne(\App\Models\ClubBankDetail::class, 'club_id');
    }

    /**
     * The ticket transction for club.
     */
    public function ticketTransaction()
    {
        return $this->hasOne(\App\Models\TicketTransaction::class, 'club_id');
    }
}
