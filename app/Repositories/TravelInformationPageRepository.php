<?php

namespace App\Repositories;

use App\Models\TravelInformationPage;
use App\Models\TravelInformationPageContent;
use Carbon\Carbon;
use DB;

/**
 * Repository class for User model.
 */
class TravelInformationPageRepository extends BaseRepository
{
    /**
     * Handle logic to create a new travel information user.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $travelInformationPage = TravelInformationPage::create([
            'club_id'          => $clubId,
            'title'            => $data['title'],
            'status'           => $data['status'],
            'photo'            => $data['logo'],
            'photo_file_name'  => $data['logo_file_name'],
            'icon'             => $data['icon'],
            'icon_file_name'   => $data['icon_file_name'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'       => $user->id,
            'updated_by'       => $user->id,

        ]);

        $travelInfoPageContentData = json_decode($data['addTravelContent']['0']);

        $displayOrder = 0;
        foreach ($travelInfoPageContentData as $key => $travelInfoPageContent) {
            $travelInformationPageContent = TravelInformationPageContent::create([
                'travel_information_page_id' => $travelInformationPage->id,
                'title'                      => $travelInfoPageContent->title,
                'content'                    => $travelInfoPageContent->description,
                'display_order'              => $displayOrder,
                'created_by'                 => $user->id,
                'updated_by'                 => $user->id,

            ]);
            $displayOrder++;
        }

        return $travelInformationPage;
    }

    /**
     * Handle logic to update a travel information.
     *
     * @param $user
     * @param $travelInformationPage
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $travelInformationPage, $data)
    {
        $travelInformationPage->fill([
            'title'            => $data['title'],
            'photo'            => $data['logo'],
            'photo_file_name'  => $data['logo_file_name'],
            'icon'             => $data['icon'],
            'icon_file_name'   => $data['icon_file_name'],
            'status'           => $data['status'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);
        $travelInformationPage->save();

        $travelInformationContentData = json_decode($data['editTravelContent']['0']);

        $displayOrder = 0;

        $travelInfoPageContentData = TravelInformationPageContent::where('travel_information_page_id', $travelInformationPage->id)->pluck('id', 'id')->toArray();

        foreach ($travelInformationContentData as $key => $travelInfoPagecontent) {
            if (in_array($travelInfoPagecontent->id, $travelInfoPageContentData)) {
                unset($travelInfoPageContentData[$travelInfoPagecontent->id]);
            }

            if ($travelInfoPagecontent->id) {
                $travelInformationPageContent = TravelInformationPageContent::where('id', $travelInfoPagecontent->id)->update([
                    'title'         => $travelInfoPagecontent->title,
                    'content'       => $travelInfoPagecontent->description,
                    'display_order' => $displayOrder,
                    'created_by'    => $user->id,
                    'updated_by'    => $user->id,
                ]);
            } else {
                $travelInfoPagecontent = TravelInformationPageContent::create([
                    'travel_information_page_id' => $travelInformationPage->id,
                    'title'                      => $travelInfoPagecontent->title,
                    'content'                    => $travelInfoPagecontent->description,
                    'display_order'              => $displayOrder,
                    'created_by'                 => $user->id,
                    'updated_by'                 => $user->id,

                ]);
            }
            $displayOrder++;
        }

        foreach ($travelInfoPageContentData as $key => $value) {
            $travelInfoPageContentData = TravelInformationPageContent::where('id', $value)->delete();
        }

        return $travelInformationPage;
    }

    /**
     * Get Travel Information data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getTravelInformationPageData($clubId, $data)
    {
        $travelInformationPageData = DB::table('travel_information_pages')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'travel_information_pages.id';
            $sorttype = 'desc';
        }
        $travelInformationPageData = $travelInformationPageData->orderBy($sortby, $sorttype);

        $newsListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $travelInformationPageData = $travelInformationPageData->paginate($data['pagination_length']);
            $newsListArray = $travelInformationPageData;
        } else {
            $newsListArray['total'] = $travelInformationPageData->count();
            $newsListArray['data'] = $travelInformationPageData->get();
        }

        $response = $newsListArray;

        return $response;
    }
}
