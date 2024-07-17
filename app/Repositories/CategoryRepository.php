<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\SpecialOffer;
use App\Models\Product;
use App\Models\ProductCollectionPoint;
use DB;

/**
 * Repository class for model.
 */
class CategoryRepository extends BaseRepository
{
    /**
     * Get Match data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $categoryData = DB::table('categories')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'categories.id';
            $sorttype = 'desc';
        }

        $categoryData = $categoryData->orderBy($sortby, $sorttype);

        $categoryListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $categoryData = $categoryData->paginate($data['pagination_length']);
            $categoryListArray = $categoryData;
        } else {
            $categoryListArray['total'] = $categoryData->count();
            $categoryListArray['data'] = $categoryData->get();
        }

        $response = $categoryListArray;

        return $response;
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
        $category = Category::create([
            'club_id'                     => $clubId,
            'title'                       => $data['title'],
            'type'                        => $data['type'],
            'image'                       => $data['logo'],
            'image_file_name'             => $data['logo_file_name'],
            'rewards_percentage_override' => $data['rewards_percentage_override'],
            'status'                      => $data['status'],
            'is_restricted_to_over_age'   => isset($data['is_restricted_to_over_age']) ? 1 : 0,
            'created_by'                  => $user->id,
            'updated_by'                  => $user->id,
        ]);

        return $category;
    }

    /**
     * Handle logic to update a category.
     *
     * @param $user
     * @param $category
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $category, $data)
    {
        $category->fill([
            'title'                       => $data['title'],
            'type'                        => $data['type'],
            'image'                       => $data['logo'],
            'image_file_name'             => $data['logo_file_name'],
            'rewards_percentage_override' => $data['rewards_percentage_override'],
            'status'                      => $data['status'],
            'is_restricted_to_over_age'   => isset($data['is_restricted_to_over_age']) ? 1 : 0,
            'updated_by'                  => $user->id,
        ]);
        $category->save();

        return $category;
    }

    /**
     * Handle logic to update a category.
     *
     * @param $clubId
     * @param $type
     *
     * @return mixed
     */
    public function getSwipeActionItems($clubId, $type)
    {
        return Category::where('club_id', $clubId)->where('type', $type)->get();
    }

    /**
     * Handle logic to get products
     *
     * @param $productCollectionPointIds
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getProducts($productCollectionPointIds, $consumerAge = "")
    {
        $products = Product::whereIn('id', $productCollectionPointIds);
        if($consumerAge < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $products = $products->where('is_restricted_to_over_age', 0);
        }
        return $products->get();
    }

    /**
     * Handle logic to get categories
     *
     * @param $clubId
     * @param $type
     * @param $categoryIds
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getCategories($clubId, $type, $categoryIds, $consumerAge = "")
    {
        $categories = Category::whereIn('id', $categoryIds)->where('club_id', $clubId)->where('type', $type)->where('status', 'Published');

        if($consumerAge < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $categories = $categories->where('is_restricted_to_over_age', 0);
        }

        return $categories->get();
    }

    /**
     * Handle logic to get special offers.
     *
     * @param $clubId
     * @param $type
     * @param $specialOfferIds
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getSpecialOffers($clubId, $type, $specialOfferIds = "", $consumerAge = "")
    {
        $specialOffers = SpecialOffer::where('club_id', $clubId)->where('status', 'Published');

        if($type != "") {
            $specialOffers = $specialOffers->where('type', $type);
        }

        if($specialOfferIds != "") {
            $specialOffers = $specialOffers->whereIn('id', $specialOfferIds);
        }

        if($consumerAge < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $specialOffers = $specialOffers->where('is_restricted_to_over_age', 0);
        }

        return $specialOffers->get();
    }

    /**
     * Handle logic to get product ids based on collection point id.
     *
     * @param $collectionPointIds
     *
     * @return mixed
     */
    public function getProductBasedOnProductCollectionPointIds($collectionPointIds)
    {
        return ProductCollectionPoint::whereIn('collection_point_id', $collectionPointIds)->pluck('product_id');
    }

    /**
     * Handle logic to get products based on product ids.
     *
     * @param $productIds
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getProductBasedProductIds($productIds, $consumerAge = "")
    {
        $products = Product::whereIn('id', $productIds);

        if($consumerAge < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $products = $products->where('is_restricted_to_over_age', 0);
        }

        return $products->get();
    }
}
