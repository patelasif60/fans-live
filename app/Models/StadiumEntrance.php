<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * StadiumEntrance model class for table request.
 */
class StadiumEntrance extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stadium_entrances';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get stadium entarnce block detail.
     */
    public function stadiumEntranceBlocks()
    {
        return $this->hasMany(StadiumBlockStadiumEntrance::class, 'stadium_entrance_id');
    }
}
