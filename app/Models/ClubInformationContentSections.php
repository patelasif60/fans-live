<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubInformationContentSections extends Model
{
    
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
    protected $table = 'club_information_content_sections';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at','updated_at'];
}
