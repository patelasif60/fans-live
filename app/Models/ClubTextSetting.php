<?php

namespace App\Models;

use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class ClubTextSetting extends Model
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
    protected $table = 'club_text_settings';
}
