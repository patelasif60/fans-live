<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\Category as CategoryResource;
use App\Http\Resources\ProductCollectionPoint\ProductCollectionPoint as ProductCollectionPointResource;
use App\Http\Resources\ProductOption\ProductOption as ProductOptionResource;
use App\Models\Category;
use App\Models\ProductOption;
use App\Models\ProductCollectionPoint;
use App\Models\SpecialOffer;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\SpecialOfferRepository;

class Product extends JsonResource
{
	/**
     * The special offer repository instance.
     *
     * @var repository
     */
	protected $specialOfferRepository;

	/**
     * Create a new service instance.
     *
     * @param SpecialOfferRepository $specialOfferRepository
     */
	public function __construct($resource)
	{
		// Ensure you call the parent constructor
        parent::__construct($resource);
		$this->specialOfferRepository = new SpecialOfferRepository();
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		$finalPrice = $this->price + (($this->price * $this->vat_rate) / 100);
		$specialOffer = $this->specialOfferRepository->getSpecialOfferWithProducts($this->id);
		$specialOfferId = null;
		$specialOfferPrice = null;
		if ($specialOffer) {
			$specialOfferId = $specialOffer->id;
			$specialOfferDiscountAmount = $specialOffer->specialOfferProducts->pluck('discount_amount')[0];
			if ($specialOffer->discount_type == 'fixed_amount') {
				$specialOfferPrice = $finalPrice - $specialOfferDiscountAmount;
			} else if ($specialOffer->discount_type == 'percentage') {
				$specialOfferPrice = $finalPrice*(1-($specialOfferDiscountAmount/100));
			}
		}

		return [
			'id' => $this->id,
			'club_id' => $this->club_id,
			'title' => $this->title,
			'short_description' => $this->short_description,
			'description' => $this->description,
			'plain_description' => trim(preg_replace('/\s+/', ' ', strip_tags($this->description))),
			'image' => $this->image,
			'image_file_name' => $this->image_file_name,
			'price' => formatNumber($this->price),
			'final_price' => formatNumber($finalPrice),
			'special_offer_price' => formatNumber($specialOfferPrice),
			'special_offer_id' => $specialOfferId,
			'rewards_percentage_override' => $this->rewards_percentage_override,
			'is_restricted_to_over_age' => $this->is_restricted_to_over_age,
			'created_by_first_name' => $this->creator->first_name,
			'created_by_last_name' => $this->creator->last_name,
			'created_by_email' => $this->creator->email,
			'created_by_id' => $this->creator->id,
			'updated_by_first_name' => $this->updater->first_name,
			'updated_by_last_name' => $this->updater->last_name,
			'updated_by_email' => $this->updater->email,
			'updated_by_id' => $this->updater->id,
			'options' => ProductOptionResource::collection($this->productOption),
			'collection_points' => ProductCollectionPointResource::collection($this->productCollectionPoint),
		];
	}
}
