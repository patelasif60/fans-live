<?php

namespace App\Repositories;

use App\Models\ClubLoyaltyPointSetting;
use App\Models\LoyaltyReward;
use App\Models\CollectionPoint;
use App\Models\LoyaltyRewardCollectionPoint;
use App\Models\LoyaltyRewardOption;
use App\Models\LoyaltyRewardTransaction;
use App\Models\ProductCollectionPoint;
use App\Models\PurchasedLoyaltyRewardProductOption;
use App\Models\StadiumBlockSeat;
use DB;
use App\Models\PurchasedLoyaltyRewardProduct;

/**
 * Repository class for model.
 */
class LoyaltyRewardRepository extends BaseRepository
{
    /**
     * Get Loyalty Rewards data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $loyaltyRewardsData = DB::table('loyalty_rewards')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'loyalty_rewards.id';
            $sorttype = 'desc';
        }
        $loyaltyRewardsData = $loyaltyRewardsData->orderBy($sortby, $sorttype);

        $loyaltyRewardsListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $loyaltyRewardsData = $loyaltyRewardsData->paginate($data['pagination_length']);
            $loyaltyRewardsListArray = $loyaltyRewardsData;
        } else {
            $loyaltyRewardsListArray['total'] = $loyaltyRewardsData->count();
            $loyaltyRewardsListArray['data'] = $loyaltyRewardsData->get();
        }

        return $loyaltyRewardsListArray;
    }

    /**
     * Handle logic to create a category.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $loyaltyReward = new LoyaltyReward();
        $loyaltyReward->club_id = $clubId;
        $loyaltyReward->title = $data['title'];
        $loyaltyReward->description = $data['description'];
        $loyaltyReward->image = $data['image'];
        $loyaltyReward->image_file_name = $data['image_file_name'];
        $loyaltyReward->price_in_points = $data['price_in_points'];
        $loyaltyReward->status = $data['status'];
        $loyaltyReward->is_restricted_to_over_age = isset($data['is_restricted_to_over_age']) ? 1 : 0;
        $loyaltyReward->created_by = $user->id;
        $loyaltyReward->updated_by = $user->id;
        $loyaltyReward->save();

        return $loyaltyReward;
    }

    /**
     * Handle logic to update a loyalty reward collcetion points.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function createLoyaltyRewardCollectionPoint($data)
    {
       return LoyaltyRewardCollectionPoint::insert($data);
    }

    /**
     * Handle logic to update a loyalty reward options.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function createLoyaltyRewardOption($data, $id = null, $action = 0)
    {
        if (!empty($data)) {
            if ($action == 1) {
                $loyaltyRewardMappingDelete = LoyaltyRewardOption::where('loyalty_reward_id', $id)->delete();
            }

            return LoyaltyRewardOption::insert($data);
        } else {
            return $loyaltyRewardMappingDelete = LoyaltyRewardOption::where('loyalty_reward_id', $id)->delete();
        }
    }

    /**
     * Handle logic to update a loyalty reward.
     *
     * @param $user
     * @param $loyaltyReward
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $loyaltyReward, $data)
    {
        $loyaltyReward->title = $data['title'];
        $loyaltyReward->description = $data['description'];
        $loyaltyReward->image = $data['image'];
        $loyaltyReward->image_file_name = $data['image_file_name'];
        $loyaltyReward->price_in_points = $data['price_in_points'];
        $loyaltyReward->status = $data['status'];
        $loyaltyReward->is_restricted_to_over_age = isset($data['is_restricted_to_over_age']) ? 1 : 0;
        $loyaltyReward->updated_by = $user->id;
        $loyaltyReward->save();

        return $loyaltyReward;
    }

	/**
	 * Handle logic to create a new product.
	 *
	 * @param $data
	 */
	public function createLoyaltyRewardPurchase($data)
	{
		$loyaltyReward = new LoyaltyRewardTransaction();
		$loyaltyReward->match_id = $data['match_id'];
		$loyaltyReward->club_id = $data['club_id'];
		$loyaltyReward->consumer_id = $data['consumer_id'];
		$loyaltyReward->points = $data['points'];
		$loyaltyReward->selected_collection_time = $data['selected_collection_time'];
		$loyaltyReward->collection_time = $data['collection_time'];
		$loyaltyReward->collection_point_id = $data['collection_point_id'];
        $loyaltyReward->transaction_timestamp = $data['transaction_timestamp'];
		$loyaltyReward->save();

		return $loyaltyReward;
	}

	/**
	 * Save purchased product.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function savePurchasedLoyaltyRewardProduct($data)
	{
		$purchasedLoyaltyRewardProduct = new PurchasedLoyaltyRewardProduct();
		$purchasedLoyaltyRewardProduct->loyalty_reward_transaction_id = $data['loyalty_reward_transaction_id'];
		$purchasedLoyaltyRewardProduct->loyalty_reward_id = $data['loyalty_reward_id'];
		$purchasedLoyaltyRewardProduct->quantity = $data['quantity'];
		$purchasedLoyaltyRewardProduct->per_quantity_points = $data['per_quantity_points'];
		$purchasedLoyaltyRewardProduct->per_quantity_additional_options_point = $data['per_quantity_additional_options_point'];
		$purchasedLoyaltyRewardProduct->total_points = $data['total_points'];
		$purchasedLoyaltyRewardProduct->save();
		return $purchasedLoyaltyRewardProduct;
	}

	/**
	 * Save purchased product options.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function savePurchasedLoyaltyRewardOptions($data)
	{
		$purchasedLoyaltyRewardOption = new PurchasedLoyaltyRewardProductOption();
		$purchasedLoyaltyRewardOption->purchased_loyalty_reward_product_id = $data['purchased_loyalty_reward_product_id'];
		$purchasedLoyaltyRewardOption->loyalty_reward_option_id = $data['loyalty_reward_option_id'];
		$purchasedLoyaltyRewardOption->save();
		return $purchasedLoyaltyRewardOption;
	}

    /**
     * Handle logic to get loyalty reward transactions.
     *
     * @param $consumerId
     *
     * @return mixed
     */
    public function getLoyaltyRewardTransactions($consumerId)
    {
        return LoyaltyRewardTransaction::where('consumer_id', $consumerId)->orderBy('id', 'desc')->get();
    }

	/**
	 * Handle logic to get loyalty reward based on loyalty reward ids.
	 *
	 * @param $loyaltyRewardIds
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardBasedLoyaltyRewardIds($loyaltyRewardIds)
	{
		return LoyaltyReward::whereIn('id', $loyaltyRewardIds)->get();
	}

	/**
	 * Handle logic to get special offers.
	 *
	 * @param $clubId
	 * @param $loyaltyRewardIds
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewards($clubId, $loyaltyRewardIds = "")
	{
		$loyaltyRewards = LoyaltyReward::where('club_id', $clubId)->where('status', 'Published');
		if($loyaltyRewardIds != "") {
			$loyaltyRewards = $loyaltyRewards->whereIn('id', $loyaltyRewardIds);
		}
		return $loyaltyRewards->get();
	}

	/**
	 * Handle logic to get loyalty reward ids based on collection point id.
	 *
	 * @param $collectionPointIds
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardBasedOnLoyaltyRewardCollectionPointIds($collectionPointIds)
	{
		return LoyaltyRewardCollectionPoint::whereIn('collection_point_id', $collectionPointIds)->pluck('loyalty_reward_id');
	}

    /**
     * Get loyalty reward transactions by collection point
     *
     * @param $clubId
     * @param $collectionPointId
     *
     * @return mixed
     */
    public function getLoyaltyTransactionsForCollectionPoint($clubId, $collectionPointId)
    {
        $loyaltyRewardTransactions = LoyaltyRewardTransaction::with('purchasedLoyaltyRewardProducts')
            ->join('product_and_loyalty_reward_transaction_collections', 'product_and_loyalty_reward_transaction_collections.transaction_id', '=', 'loyalty_reward_transactions.id')
            ->select('*', 'loyalty_reward_transactions.id as id', 'product_and_loyalty_reward_transaction_collections.type as transaction_type')
            ->where('product_and_loyalty_reward_transaction_collections.type', 'loyalty_reward')
            ->where('loyalty_reward_transactions.collection_point_id', $collectionPointId)
            ->where('loyalty_reward_transactions.club_id', $clubId)
            ->where('product_and_loyalty_reward_transaction_collections.status', '!=', 'Collected')
            ->get();
        return $loyaltyRewardTransactions;
    }

    /**
     * Handle logic to get loyalty reward for age restricted consumer.
     *
     * @param $clubId
     * @param $isAgeRestricted
     * @param $loyaltyRewardProductIds=[]
     *
     * @return mixed
     */
    public function getLoyaltyRewardForAgeRestrictedConsumer($clubId, $isAgeRestricted = FALSE, $loyaltyRewardProductIds = [])
    {
        $loyaltyRewardProducts = LoyaltyReward::when($clubId, function($q) use($clubId) {
                                                    $q->where('club_id', $clubId);
                                                })
                                                ->when($isAgeRestricted, function($q){
                                                    $q->where('is_restricted_to_over_age', '=', 0);
                                                });
        if ($loyaltyRewardProductIds) {
            $loyaltyRewardProducts->whereIn('id', $loyaltyRewardProductIds);
        }
        return $loyaltyRewardProducts->get();
    }
    /**
    * Get all Data
    */
    public function getAllLoyaltyReward()
    {
        return LoyaltyRewardTransaction::all();
    }
}
