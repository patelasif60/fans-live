<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClubInformationPage extends Model
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
    protected $table = 'club_information_pages';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at','updated_at'];

    /**
    * Get the Club information page detail.
    */
    public function clubInformationPageContent()
    {
        return $this->hasMany(\App\Models\ClubInformationContentSections::class)->orderBy('display_order');
    }
}
