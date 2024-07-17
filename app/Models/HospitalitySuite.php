<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookedHospitalitySuite;

class HospitalitySuite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'club_id', 'price', 'vat_rate', 'short_description', 'long_description', 'image', 'image_file_name', 'seating_plan', 'seating_plan_file_name', 'is_active', 'dietary_options', 'created_by', 'updated_by','number_of_seat',
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
    public function hospitalitySuiteTransaction()
    {
        return $this->hasMany(HospitalitySuiteTransaction::class, 'hospitality_suite_id');
    }
    public function hospitalityDietaryOptions()
    {
        return $this->hasMany(HospitalityDietaryOptions::class, 'hospitality_suite_id');
    }

    /**
     * Get available tickets.
     */
    public function getHospitalitySuiteTickets($hospitalitySuite, $matchId)
    {
    	$hospitalityTransactionIds = $hospitalitySuite->hospitalitySuiteTransaction->where('match_id', $matchId)->pluck('id')->toArray();
    	$availableTickets = 0;
    	if(count($hospitalityTransactionIds) > 0) {
    		$availableTickets = BookedHospitalitySuite::whereIn('hospitality_suite_transaction_id', $hospitalityTransactionIds)->max('seat');
    	}
        return $availableTickets;
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
