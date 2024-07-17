<?php

namespace App\Repositories;

use App\Models\ClubCategory;
use DB;

/**
 * Repository class for  model.
 */
class ClubCategoryRepository extends BaseRepository
{
    /**
     * Handle logic to create a category.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($user, $data)
    {
        $category = ClubCategory::create([
            'name'           => $data['name'],
            'logo'           => $data['logo'],
            'logo_file_name' => $data['logo_file_name'],
            'status'         => $data['status'],
            'created_by'     => $user->id,
            'updated_by'     => $user->id,
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
            'name'           => $data['name'],
            'logo'           => $data['logo'],
            'logo_file_name' => $data['logo_file_name'],
            'status'         => $data['status'],
            'created_by'     => $user->id,
            'updated_by'     => $user->id,
        ]);
        $category->save();

        return $category;
    }

    /**
     * Get Category data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $categoryData = DB::table('club_categories');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'club_categories.id';
            $sorttype = 'desc';
        }
        $categoryData = $categoryData->orderBy($sortby, $sorttype);

        if (isset($data['name']) && trim($data['name']) != '') {
            $categoryData->where('club_categories.name', 'like', '%'.$data['name'].'%');
        }

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
	 * Handle logic to get Club Category Count.
	 *
	 *
	 * @return mixed
	 */
	public function getClubCategoryCount()
	{
		$clubCategoryCount = ClubCategory::count();
		return $clubCategoryCount;
	}

}
