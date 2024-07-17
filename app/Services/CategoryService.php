<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use File;
use Storage;

/**
 * Category class to handle category interactions.
 */
class CategoryService
{
    /**
     * The category repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * The category image path.
     *
     * @var logoPath
     */
    protected $logoPath;

    /**
     * Create a new service instance.
     *
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
        $this->logoPath = config('fanslive.IMAGEPATH.category_logo');
    }

    /**
     * Get category data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $category = $this->repository->getData($clubId, $data);

        $categorydata = $category;
        if (is_array($category)) {
            $categorydata = $category['data'];
        }

        $categorydata->every(function ($value, $key) {
            $value->type = config('fanslive.CATEGORY_TYPE')[$value->type];

            return $value;
        });

        if (is_array($category)) {
            $category['data'] = $categorydata;
        }

        return $category;
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
        if (isset($data['logo'])) {
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = null;
            $data['logo_file_name'] = null;
        }

        $category = $this->repository->create($clubId, $user, $data);

        return $category;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $user
     * @param $category
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $category, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$category->image_file_name;
            $disk->delete($existingLogo);
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = $category->image;
            $data['logo_file_name'] = $category->image_file_name;
        }
        $categoryToUpdate = $this->repository->update($user, $category, $data);

        return $categoryToUpdate;
    }

    /**
     * Handle logic to delete a given logo file.
     *
     * @param $category
     *
     * @return mixed
     */
    public function deleteLogo($category)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$category->image_file_name;

        return $disk->delete($logo);
    }

    /**
     * Handle logic to get categories.
     *
     * @param $clubId
     * @param $type
     * @param $productCollectionPointIds
     *
     * @return mixed
     */
    public function getCategories($clubId, $type, $productCollectionPointIds, $consumerAge = "")
    {
        $products = $this->repository->getProducts($productCollectionPointIds, $consumerAge);
        return $this->getCategoryData($clubId, $type, $products, $consumerAge);
    }

    /**
     * Handle logic to get categories.
     *
     * @param $clubId
     * @param $type
     * @param $products
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getCategoryData($clubId, $type, $products, $consumerAge = "")
    {
        $categoryIds = [];
        foreach ($products as $product) {
           foreach ($product->categories as $category) {
                $categoryIds[] = $category->id;
           }
        }

        $categoryIds = array_unique($categoryIds);

        return $this->repository->getCategories($clubId, $type, $categoryIds, $consumerAge);
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
    public function getSpecialOffers($clubId, $type = "", $specialOfferIds = "", $consumerAge = "")
    {
        return $this->repository->getSpecialOffers($clubId, $type, $specialOfferIds, $consumerAge);
    }

    /**
     * Handle logic to get special offers.
     *
     * @param $clubId
     * @param $type
     * @param $products
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getSpecialOffersData($clubId, $type, $products, $consumerAge = "")
    {
        $specialOfferIds = [];

        foreach ($products as $product) {
           foreach ($product->specialOffers as $specialOffer) {
                $specialOfferIds[] = $specialOffer->id;
           }
        }
        $specialOfferIds = array_unique($specialOfferIds);

        return $this->repository->getSpecialOffers($clubId, $type, $specialOfferIds, $consumerAge);
    }

    /**
     * Handle logic to get products based on seat.
     *
     * @param $blockId
     * @param $consumerAge
     *
     * @return mixed
     */
    public function getProductsBasedOnSeat($blockId, $stadiumBlock, $consumerAge = "")
    {
        $collectionPointIds = $stadiumBlock->collectionPoints->pluck('collection_point_id');

        $productIds = $this->repository->getProductBasedOnProductCollectionPointIds($collectionPointIds);

        return $this->repository->getProductBasedProductIds($productIds, $consumerAge);
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->repository);
        unset($this->logoPath);
    }
}
