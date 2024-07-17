<?php

namespace App\Models;

// use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TravelInformationPage extends Model
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
    protected $table = 'travel_information_pages';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the Travel information page detail.
     */
    public function travelInformationPageContent()
    {
        return $this->hasMany(\App\Models\TravelInformationPageContent::class)->orderBy('display_order');
    }
}
