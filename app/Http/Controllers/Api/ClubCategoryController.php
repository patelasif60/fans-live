<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClubCategory\ClubCategory as ClubCategoryResource;
use App\Models\ClubCategory;
use App\Services\ClubCategoryService;
use Illuminate\Http\Request;

/**
 * @group Club category
 *
 * APIs for Club category.
 */
class ClubCategoryController extends BaseController
{
    /**
     * Create a category service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClubCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Get club categories
     * Get all published club categories that having atleast 1 club.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories(Request $request)
    {
        $categories = ClubCategory::has('publishedClubs', '>', 0)->where('status', 'Published')->get();

        return ClubCategoryResource::collection($categories);
    }
}
