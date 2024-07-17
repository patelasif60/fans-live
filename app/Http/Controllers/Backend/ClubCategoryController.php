<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClubCategory\StoreRequest;
use App\Http\Requests\ClubCategory\UpdateRequest;
use App\Models\ClubCategory;
use App\Services\ClubCategoryService;
use Illuminate\Http\Request;

class ClubCategoryController extends Controller
{
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
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.clubcategories.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.clubcategories.create', compact('categoryStatus'));
    }

    /**
     * Store a newly created category.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $category = $this->service->create(
            auth()->user(),
            $request->all()
        );

        if ($category) {
            flash('Category created successfully')->success();
        } else {
            flash('Category could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.clubcategory.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ClubCategory $category)
    {
        $categoryStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.clubcategories.edit', compact('categoryStatus', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ClubCategory $category)
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

        return redirect()->route('backend.clubcategory.index');
    }

    /**
     * Remove the specified category.
     *
     * @param  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClubCategory $category)
    {
        $categoryLogoToDelete = $this->service->deleteLogo(
            $category
        );

        if ($categoryLogoToDelete && $category->delete()) {
            flash('Category deleted successfully')->success();
        } elseif (!$categoryLogoToDelete && $category->delete()) {
            flash('Category deleted successfully')->success();
        } else {
            flash('Category could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.clubcategory.index');
    }

    /**
     * Get Category list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClubCategoryData(Request $request)
    {
        $categoryList = $this->service->getData(
            $request->all()
        );

        return $categoryList;
    }
}
