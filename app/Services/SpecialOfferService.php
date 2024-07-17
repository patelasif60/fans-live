<?php

namespace App\Services;

use App\Repositories\SpecialOfferRepository;
use File;
use Storage;

/**
 * Special Offer Service class to handle operator interactions.
 */
class SpecialOfferService
{
	/**
	 * The special offer repository instance.
	 *
	 * @var repository
	 */
	protected $repository;

	/**
	 * @var predefined image path
	 */
	protected $imagePath;

	/**
	 * Create a new offer instance.
	 *
	 * @param SpecialOfferRepository $repository
	 */
	public function __construct(SpecialOfferRepository $repository)
	{
		$this->repository = $repository;
		$this->imagePath = config('fanslive.IMAGEPATH.special_offer_image');
	}

	/**
	 * Handle logic to get product list.
	 *
	 * @param $clubId
	 * @param $data
	 * @param $excludeProductIds
	 *
	 * @return mixed
	 */
	public function getProducts($clubId, $data, $excludeProductIds = [])
	{
		$productList = $this->repository->getProducts($clubId, $excludeProductIds);
		$productData=[];
		foreach($productList as $productKey => $product){
			$foodAndDrinkCount=$merchandise=0;
			foreach($product->categories as $key=> $value)
			{
				if($foodAndDrinkCount==0)
				{
					if($value->type == 'food_and_drink')
					{
						$productData['food_and_drink'][$product->id] = ['title' => $product->title, 'final_price' => $product->final_price];
						$foodAndDrinkCount++;
					}
				}
				if($merchandise==0)
				{
					if($value->type == 'merchandise')
					{
						$productData['merchandise'][$product->id] = ['title' => $product->title, 'final_price' => $product->final_price];
						$merchandise++;
					}
				}
			}
		}
		if($data =='merchandise') {
			return isset($productData['merchandise']) ? $productData['merchandise'] : null;
		}
		return isset($productData['food_and_drink']) ? $productData['food_and_drink'] : null;
	}

	/**
	 * Handle logic to get product list.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getMembershipPlans($clubId)
	{
		return $this->repository->getMembershipPlans($clubId);
	}

	/**
	 * Handle logic to get only event membership package.
	 *
	 * @param $specialoffer
	 *
	 * @return mixed
	 */
	public function getOfferMembershipPackage($specialoffer)
	{
		$getOfferMembershipPackage = $this->repository->getOfferMembershipPackage($specialoffer);

		$offerPackages = [];
		foreach ($getOfferMembershipPackage as $key => $offerPackage) {
			$offerPackages = array_column($offerPackage->specialOfferMembershipPackageAvailability->toArray(), 'membership_package_id');
		}
		return $offerPackages;
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
		if (isset($data['image'])) {
			$image = uploadImageToS3($data['image'], $this->imagePath);
			} else {
			$image['url'] = null;
			$image['file_name'] = null;
		}

		$data['image'] = $image['url'];
		$data['image_file_name'] = $image['file_name'];

		$specialOffer = $this->repository->create($clubId, $user, $data);

		if (!empty($specialOffer)) {

			// Insert special offer product list
			$this->createSpecialOfferProductList($specialOffer, $data);
		}

		if (!empty($specialOffer)) {

			// Insert special offer membership package available
			$this->createSpecialOfferMembershipPackageAvailability($specialOffer, $data);
		}

		return $specialOffer;

	}

	/**
	 * Handle logic to create or update a Special offer product list.
	 *
	 * @param $specialOffer
	 * @param $data
	 * @param $action
	 *
	 * @return mixed
	 */
	protected function createSpecialOfferProductList($specialOffer, $data, $action = 0)
	{
		$dbFields = [];
		$id = null;
		if (!empty($data['products'])) {
			foreach ($data['products'] as $key => $val) {
				$dbFields[] = [
					'product_id' => $data['product_id'][$val],
					'special_offer_id' => $specialOffer->id,
					'discount_amount' => $data['discount_amount'][$val] ? $data['discount_amount'][$val] : 0,
				];
			}
		}

		if ($action == 1) {
			$id = $specialOffer->id;
		}
		//For Special Offer Product List
		$this->repository->createSpecialOfferProductList($dbFields, $id, $action);

		return $specialOffer;
	}

	/**
	 * Handle logic to create or update a Special offer membership package.
	 *
	 * @param $specialOffer
	 * @param $data
	 * @param $action
	 *
	 * @return mixed
	 */
	protected function createSpecialOfferMembershipPackageAvailability($specialOffer, $data, $action = 0)
	{
		$dbFields = [];
		$id = null;
		if (!empty($data['packageList'])) {
			foreach ($data['packageList'] as $key => $val) {
				$dbFields[] = [
					'membership_package_id' => $data['packageList'][$key],
					'special_offer_id' => $specialOffer->id,
				];
			}
		}

		if ($action == 1) {
			$id = $specialOffer->id;
		}
		//For Special Offer membership package available
		$this->repository->createSpecialOfferMembershipPackageAvailability($dbFields, $id, $action);

		return $specialOffer;

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
		$specialOffer = $this->repository->getData($clubId, $data);

		return $specialOffer;
	}

	/**
	 * Handle logic to update a given news.
	 *
	 * @param $user
	 * @param $news
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $specialoffer, $data)
	{
		if (isset($data['image'])) {
			$image = uploadImageToS3($data['image'], $this->imagePath);
			$data['image'] = $image['url'];
			$data['image_file_name'] = $image['file_name'];
		} else {
			$data['image'] = $specialoffer->image;
			$data['image_file_name'] = $specialoffer->image_file_name;
		}

		$specialOfferToUpdate = $this->repository->update($user, $specialoffer, $data);
		if (!empty($specialoffer)) {

			// update offer product
			$this->createSpecialOfferProductList($specialoffer, $data, $action = 1);

			// update offer membership package
			$this->createSpecialOfferMembershipPackageAvailability($specialoffer, $data, $action = 1);
		}

		return $specialoffer;
	}

}
