<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'special_offers';
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * Get Special offer products.
	 */
	public function specialOfferProducts()
	{
		return $this->hasMany(SpecialOfferProduct::class, 'special_offer_id');
	}

	/**
	 * Get special offer membership package.
	 */
	public function specialOfferMembershipPackageAvailability()
	{
		return $this->hasMany(SpecialOfferMembershipPackageAvailability::class, 'special_offer_id');
	}

}
