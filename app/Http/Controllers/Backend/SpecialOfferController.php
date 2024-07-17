<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use Illuminate\Http\Request;
use App\Services\SpecialOfferService;
use App\Models\Product;
use App\Models\PurchasedProduct;
use App\Http\Requests\SpecialOffer\UpdateRequest;
use Javascript;

class SpecialOfferController extends Controller
{

	/**
	 * The collection point service instance.
	 *
	 * @var service
	 */
	public function __construct(SpecialOfferService $service)
	{
		$this->service = $service;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$specialOfferType = config('fanslive.CATEGORY_TYPE');
		Javascript::put([
			'specialOfferType' => json_encode($specialOfferType)
		]);
		return view('backend.specialoffer.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($club)
	{
		$clubId = getClubIdBySlug($club);
		$specialOfferStatus = config('fanslive.PUBLISH_STATUS_SPECIAL_OFFER');
		$specialOfferType = config('fanslive.CATEGORY_TYPE');
		$membershipPackageList = $this->service->getMembershipPlans($clubId);
		$specialOffers = SpecialOffer::with('specialOfferProducts')->where('club_id', $clubId)->where('status','!=','Archived')->get();
		$specialOfferProductIds = [];
		if ($specialOffers) {
			$specialOfferProductIds = $specialOffers->pluck('specialOfferProducts')->collapse()->pluck('product_id')->toArray();
		}
		$productList = $this->service->getProducts($clubId, 'food_and_drink', $specialOfferProductIds);
		return view('backend.specialoffer.create', compact('specialOfferStatus', 'specialOfferType', 'productList', 'membershipPackageList'));
	}
	 /**
     * change type wise product .
     *
     * @return \Illuminate\Http\Response
     */
    public function getTypewiseProduct(Request $request, $clubId)
    {
        $clubId = getClubIdBySlug($clubId);
    	$data = $request->all();
    	$specialOffers = SpecialOffer::with('specialOfferProducts')->where('club_id', $clubId)->where('status','!=','Archived')->get();
		$specialOfferProductIds = [];
		if ($specialOffers) {
			$specialOfferProductIds = $specialOffers->pluck('specialOfferProducts')->collapse()->pluck('product_id')->toArray();
		}
    	$productList = $this->service->getProducts($clubId,$data['type'],$specialOfferProductIds);
        return $productList;
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param $club
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$specialOffer = $this->service->create(
			$clubId,
			auth()->user(),
			$request->all()
		);

		if ($specialOffer) {
			flash('Special Offer created successfully')->success();
		} else {
			flash('Special Offer could not be created. Please try again.')->error();
		}

		return redirect()->route('backend.specialoffer.index', ['club' => app()->request->route('club')]);


	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $club
	 * @param $specialOffer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $club, SpecialOffer $specialoffer)
	{
		$clubId = getClubIdBySlug($club);
		$specialOfferType = config('fanslive.CATEGORY_TYPE');
		$specialOfferStatus = config('fanslive.PUBLISH_STATUS_SPECIAL_OFFER');

		// Get all membership package
		$membershipPackageList = $this->service->getMembershipPlans($clubId);

		// Get only offer membership package
		$offerMembershipPackageList = $this->service->getOfferMembershipPackage($specialoffer);

		$specialOffers = SpecialOffer::with('specialOfferProducts')->where('club_id', $clubId)->where('status','!=','Archived')->where('id', '!=', $specialoffer->id)->get();
		$specialOfferProductIds = [];
		if ($specialOffers) {
			$specialOfferProductIds = $specialOffers->pluck('specialOfferProducts')->collapse()->pluck('product_id')->toArray();
		}
		
		// Get all product list
		$productList = $this->service->getProducts($clubId, $specialoffer->type, $specialOfferProductIds);

		// Get only offer product list
		$selectedProductList = $specialoffer->specialOfferProducts->pluck('product_id')->toArray();
		$selectedProductListEdit = $specialoffer->specialOfferProducts()->orderBy('product_id')->get()->toArray();

		return view('backend.specialoffer.edit', compact('specialoffer', 'specialOfferType', 'specialOfferStatus', 'membershipPackageList', 'offerMembershipPackageList', 'productList', 'selectedProductList', 'selectedProductListEdit'));

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRequest $request, $clubId, SpecialOffer $specialoffer)
	{
		$specialOfferToUpdate = $this->service->update(
			auth()->user(),
			$specialoffer,
			$request->all()
		);

		if ($specialOfferToUpdate) {
			flash('Special Offer updated successfully')->success();
		} else {
			flash('Special Offer could not be updated. Please try again.')->error();
		}

		return redirect()->route('backend.specialoffer.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Delete the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  $clubId
	 * @param $specialoffer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $clubId, SpecialOffer $specialoffer)
	{
		$purchasedProduct = PurchasedProduct::with(['productTransaction' => function($q) {
			$q->where('status','successful');
		}])->whereHas('productTransaction', function($q){
			$q->where('status','successful');
		})->where('special_offer_id', $specialoffer->id)->get();
		if (count($purchasedProduct) == 0) {
			if ($specialoffer->delete()) {
				return response()->json(['status'=>'success', 'message'=>'Special offer deleted successfully']);
			} else {
				return response()->json(['status'=>'error', 'message'=>'Special Offer could not be deleted. Please try again.']);
			}
		} else {
			return response()->json(['status'=>'error', 'message'=>'This special offer cannot be deleted as transactions have been completed using this special offer.']);
		}
	}

	/**
	 * Get Travel offers list data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getSpecialOfferData(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$specialOfferList = $this->service->getData(
			$clubId,
			$request->all()
		);
		return $specialOfferList;
	}

}
