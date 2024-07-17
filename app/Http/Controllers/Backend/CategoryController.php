<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

/**
 * Category Controller class to handle request.
 */
class CategoryController extends Controller
{
    /**
     * The category service instance.
     *
     * @var service
     */
    public function __construct(CategoryService $service)
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
        return view('backend.categories.index');
    }

    /**
     * Show the form for creating a category resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
        $categoryStatus = config('fanslive.PUBLISH_STATUS');
        $categoryType = config('fanslive.CATEGORY_TYPE');
        \Log::info("hello world");

        return view('backend.categories.create', compact('categoryStatus', 'categoryType'));
    }

    /**
     * Store a category created resource in storage.
     *
     * @param \App\Http\Requests\Category\StoreRequest $request
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $category = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($category) {
            flash('Category created successfully')->success();
        } else {
            flash('Category could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.category.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Category $category)
    {
        $clubId = getClubIdBySlug($clubId);

        $categoryStatus = config('fanslive.PUBLISH_STATUS');
        $categoryType = config('fanslive.CATEGORY_TYPE');

        return view('backend.categories.edit', compact('categoryStatus', 'categoryType', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Category\UpdateRequest $request
     * @param  $clubId
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Category $category)
    {
        $categoryToUpdate = $this->service->update(
            auth()->user(),
            $category,
            $request->all()
        );

        if ($categoryToUpdate) {
            flash('Category updated successfully')->success();
        } else {
            flash('Category could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.category.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Category $category)
    {
        $categoryLogoToDelete = $this->service->deleteLogo($category);
        if ($category->delete()) {
            flash('Category deleted successfully')->success();
        } else {
            flash('Category could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.category.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Category list data.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $categoryList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $categoryList;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
