<?php

namespace App\Repositories;

use App\Models\StadiumBlock;
use App\Models\Product;
use App\Models\CollectionPoint;
use App\Models\CollectionPointStadiumBlock;
use App\Models\ProductAndLoyaltyRewardTransactionCollection;
use Carbon\Carbon;
use DB;

/**
 * Repository class for model.
 */
class CollectionPointRepository extends BaseRepository
{
	/**
	 * Handle logic to create a collection point.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		$collectionPoint = new CollectionPoint();
		$collectionPoint->club_id = $clubId;
		$collectionPoint->title = $data['title'];
		$collectionPoint->status = $data['status'];
		$collectionPoint->created_by = $user->id;
		$collectionPoint->updated_by = $user->id;
		$collectionPoint->save();
		$product = Product::all()->where('status','Published')->pluck('id');
		$collectionPoint->products()->sync($product);
		return $collectionPoint;
	}

	/**
	 * Handle logic to update a collection point stadium block.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createCollectionPointStadiumBlock($data, $id = null, $action = 0)
	{
		if (!empty($data)) {
			if ($action == 1) {
				$loyaltyRewardMappingDelete = CollectionPointStadiumBlock::where('collection_point_id', $id)->delete();
			}

			return CollectionPointStadiumBlock::insert($data);
		} else {
			return $loyaltyRewardMappingDelete = CollectionPointStadiumBlock::where('collection_point_id', $id)->delete();
		}
	}

	/**
	 * Get Competitoin data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{

		$collectionPointsData = CollectionPoint::leftJoin('collection_point_stadium_block', 'collection_point_stadium_block.collection_point_id', '=', 'collection_points.id')
			->leftJoin('stadium_blocks', 'collection_point_stadium_block.stadium_block_id', '=', 'stadium_blocks.id')
			->selectRaw('collection_points.id, collection_points.club_id, collection_points.title, collection_points.status, GROUP_CONCAT(stadium_blocks.name SEPARATOR ", ") as stadium_block_name')
			->where('collection_points.club_id', $clubId);

		$groupby = ['collection_points.id', 'collection_points.club_id', 'collection_points.title', 'collection_points.status'];

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'collection_points.id';
			$sorttype = 'desc';
		}
		$collectionPointsData = $collectionPointsData->groupBy($groupby)->orderBy($sortby, $sorttype);

		$collectionPointsListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$collectionPointsData = $collectionPointsData->paginate($data['pagination_length']);
			$collectionPointsListArray = $collectionPointsData;
		} else {
			$collectionPointsListArray['total'] = $collectionPointsData->get()->count();
			$collectionPointsListArray['data'] = $collectionPointsData->get();
		}

		$response = $collectionPointsListArray;

		return $response;
	}
	/**
	 * Handle logic to update a news.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $collectionpoint, $data)
	{
		$collectionpoint->fill([
			'title'           => $data['title'],
			'status'          => $data['status'],
			'created_by'      => $user->id,
			'updated_by'      => $user->id,
		]);
		$collectionpoint->save();

		return $collectionpoint;
	}
	/**
	 * Handle logic to update a news.
	 *
	 * @param $collectionPoint
	 * @param $status
	 * @param $staffId
	 *
	 * @return mixed
	 */
	public function updateOrderStatus($collectionPoint, $status, $staffId)
	{
		$collectionPoint->fill([
			'staff_id'        => $staffId ? $staffId : null,
			'status'          => $status,
			'collected_time'  => $status === 'Collected' ? Carbon::now()->format('Y-m-d H:i:s') : null,
		]);
		$collectionPoint->save();

		return $collectionPoint;
	}

	/**
	 * Handle logic to create new product and loyalty reward transaction.
	 *
	 * @param $transactionId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function createProductAndLoyaltyRewardTransaction($transactionId, $type)
	{
		$productAndLoyaltyRewardTransaction = new ProductAndLoyaltyRewardTransactionCollection();
		$productAndLoyaltyRewardTransaction->transaction_id = $transactionId;
		$productAndLoyaltyRewardTransaction->type = $type;
		$productAndLoyaltyRewardTransaction->status = 'New';
		$productAndLoyaltyRewardTransaction->save();

		return $productAndLoyaltyRewardTransaction;
	}

	/**
     * Get collection points.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getCollectionPoints($clubId)
    {
        return CollectionPoint::where('club_id', $clubId)->get();
    }

}
