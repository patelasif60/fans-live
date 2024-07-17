<?php

namespace App\Models;

use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class ClubOpeningTimeSetting extends Model
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
    protected $table = 'club_opening_time_settings';
}
