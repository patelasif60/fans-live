<?php

namespace App\Repositories;

use App\Models\MembershipPackage;
use App\Models\Product;
use App\Models\SpecialOffer;
use App\Models\SpecialOfferProduct;
use App\Models\SpecialOfferMembershipPackageAvailability;
use DB;

/**
 * Repository class for model.
 */
class SpecialOfferRepository extends BaseRepository
{
	/**
	 * Handle logic to get product list.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getProducts($clubId, $excludeProductIds = [])
	{
		$productList = Product::where('club_id', $clubId)
							->when($excludeProductIds, function($q) use($excludeProductIds) {
								$q->whereNotIn('id', $excludeProductIds);
							})
							->get();
		return $productList;
	}

	/**
	 * Handle logic to get membership plan.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getMembershipPlans($clubId)
	{
		$membershipPlans = MembershipPackage::where('club_id', $clubId)
			->orWhere('club_id', NULL)->orderBy('id')
			->get()->pluck('title', 'id');
		return $membershipPlans;
	}


	/**
	 * Handle logic to create a special offer.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		DB::beginTransaction();
		try {
			$specialOffer = SpecialOffer::create([

				'club_id' => $clubId,
				'title' => $data['title'],
				'type' => $data['type'],
				'image' => $data['image'],
				'image_file_name' => $data['image_file_name'],
				'is_restricted_to_over_age' => isset($data['is_restricted_to_over_age']) ? 1 : 0,
				'discount_type' => $data['discount_type'],
				'status' => $data['status'],
				'created_by' => $user->id,
				'updated_by' => $user->id,
			]);

			DB::commit();

			return $specialOffer;
		} catch (\Exception $e) {
			//dd($e);
			DB::rollback();

			return null;
		}
	}

	/**
	 * Handle logic to update a special offer product list.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createSpecialOfferProductList($data, $id = null, $action = 0)
	{
		if (!empty($data)) {
			if ($action == 1) {
				$specialOfferProductDelete = SpecialOfferProduct::where('special_offer_id', $id)->delete();
			}

			return SpecialOfferProduct::insert($data);
		} else {
			return $specialOfferProductDelete = SpecialOfferProduct::where('special_offer_id', $id)->delete();
		}
	}

	/**
	 * Handle logic to update a special offer Membership Package.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createSpecialOfferMembershipPackageAvailability($data, $id = null, $action = 0)
	{
		if (!empty($data)) {
			if ($action == 1) {
				$specialOfferMembershipPackageDelete = SpecialOfferMembershipPackageAvailability::where('special_offer_id', $id)->delete();
			}

			return SpecialOfferMembershipPackageAvailability::insert($data);
		} else {
			return $specialOfferMembershipPackageDelete = SpecialOfferMembershipPackageAvailability::where('special_offer_id', $id)->delete();
		}
	}

	/**
	 * Handle logic to get only special offer membership package.
	 *
	 * @param $specialoffer
	 *
	 * @return mixed
	 */
	public function getOfferMembershipPackage($specialoffer)
	{
		$getOfferPackage = $specialoffer::with(['specialOfferMembershipPackageAvailability'])->where('id', $specialoffer->id)->get();

		return $getOfferPackage;
	}

	/**
	 * Get special offer data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$specialOffersData = SpecialOffer::where('club_id', $clubId);

		$groupby = 'id';

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'id';
			$sorttype = 'desc';
		}
		// $specialOffersData = $specialOffersData->groupBy($groupby)->orderBy($sortby, $sorttype);
		$specialOffersData = $specialOffersData->orderBy($sortby, $sorttype);

		$specialOffersListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$specialOffersData = $specialOffersData->paginate($data['pagination_length']);
			$specialOffersListArray = $specialOffersData;
		} else {
			$specialOffersListArray['total'] = $specialOffersData->count();
			$specialOffersListArray['data'] = $specialOffersData->get();
		}

		$response = $specialOffersListArray;

		return $response;
	}

	/**
	 * Handle logic to update a offer.
	 *
	 * @param $user
	 * @param $specialoffer
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $specialoffer, $data)
	{
		DB::beginTransaction();

		try {
			$specialoffer->fill([
				'title' => $data['title'],
				'type' => $data['type'],
				'image' => $data['image'],
				'image_file_name' => $data['image_file_name'],
				'is_restricted_to_over_age' => isset($data['is_restricted_to_over_age']) ? 1 : 0,
				'discount_type' => $data['discount_type'],
				'status' => $data['status'],
				'updated_by' => $user->id,
			]);

			$specialoffer->save();
			DB::commit();

			return $specialoffer;
		} catch (\Exception $e) {
			DB::rollback();
			return null;
		}
	}

	/**
	 * Get special offer with products
	 *
	 * @param $productId
	 * @param $specialOfferId
	 *
	 * @return mixed
	 */
	public function getSpecialOfferWithProducts($productId = null, $specialOfferId = null)
	{
		$specialOffer = SpecialOffer::with(['specialOfferProducts' => function($q) use($productId) {
										$q->where('product_id', $productId);
									}])
									->whereHas('specialOfferProducts', function($q) use($productId) {
										$q->where('product_id', $productId);
									})
									->when($specialOfferId, function($q) use($specialOfferId) {
										$q->where('id', $specialOfferId);
									})
									->first();
		return $specialOffer;
	}
}
