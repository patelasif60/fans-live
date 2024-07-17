<?php
namespace App\Repositories;

use DB;
use Carbon\Carbon;
use App\Models\ClubInformationPage;
use App\Models\ClubInformationContentSections;

/**
 * Repository class for User model.
 */
class ClubInformationPageRepository extends BaseRepository
{
    /**
    * Handle logic to create a new club information user.
    *
    * @param $clubId
    * @param $user
    * @param $data
    * @return mixed
    */
	public function create($clubId, $user, $data)
    {
        $clubInformationPage = ClubInformationPage::create([
            'club_id' => $clubId,
            'title' => $data['title'],
            'status' => $data['status'],
            'icon' => @$data['icon'],
            'icon_file_name' => @$data['icon_file_name'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);

        $clubInfoPageContentData = json_decode($data['addClubContent']['0']);
        
        $displayOrder = 0;
        foreach ($clubInfoPageContentData as $key => $clubInfoPageContent) {
            $clubInformationPageContent = ClubInformationContentSections::create([
                'club_information_page_id' => $clubInformationPage->id,
                'title' =>  $clubInfoPageContent->title,
                'content' => $clubInfoPageContent->description,
                'display_order' => $displayOrder,
                'created_by' => $user->id,
                'updated_by' => $user->id
            ]);
            $displayOrder++;
        }
        return $clubInformationPage;
    }

    /**
    * Handle logic to update a club information.
    *
    * @param $user
    * @param $clubInformationPage
    * @param $data
    * @return mixed
    */
    public function update($user, $clubInformationPage, $data)
    {
        $clubInformationPage->fill([
            'title' => $data['title'],
            'icon' => @$data['icon'],
            'icon_file_name' => @$data['icon_file_name'],
            'status' => $data['status'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        $clubInformationPage->save();

        $clubInformationContentData = json_decode($data['editClubContent']['0']);

        $displayOrder = 0;

        $clubInfoPageContentData = ClubInformationContentSections::where('club_information_page_id', $clubInformationPage->id)->pluck('id', 'id')->toArray();

        
        foreach ($clubInformationContentData as $key => $clubInfoPagecontent) {

            if(in_array($clubInfoPagecontent->id, $clubInfoPageContentData)) 
            {
                unset($clubInfoPageContentData[$clubInfoPagecontent->id]);
            }


            if($clubInfoPagecontent->id) {
                $ClubInformationContentSections = ClubInformationContentSections::where('id', $clubInfoPagecontent->id)->update([
                    'title' => $clubInfoPagecontent->title,
                    'content' => $clubInfoPagecontent->description,
                    'display_order' => $displayOrder,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                ]);
            } else {
                $clubInfoPagecontent = ClubInformationContentSections::create([
                    'club_information_page_id' => $clubInformationPage->id,
                    'title' =>  $clubInfoPagecontent->title,
                    'content' => $clubInfoPagecontent->description,
                    'display_order' => $displayOrder,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                
                 ]);
            }
        $displayOrder++;    
        }
        
        foreach ($clubInfoPageContentData as $key => $value) {
            $clubInfoPageContentData = ClubInformationContentSections::where('id', $value)->delete();
        }
    
        return $clubInformationPage;
    }

    /**
    * Get Club Information data
    *
    * @param $clubId
    * @param $data
    * @return mixed
    */
    public function getClubInformationPageData($clubId, $data)
    {
       $clubInformationPageData = DB::table('club_information_pages')->where('club_id', $clubId);

        if(isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'club_information_pages.id';
            $sorttype = 'desc';
        }
        $clubInformationPageData = $clubInformationPageData->orderBy($sortby, $sorttype);

        $newsListArray = array();
        if(!array_key_exists('pagination', $data)) {
            $clubInformationPageData = $clubInformationPageData->paginate($data['pagination_length']);
            $newsListArray = $clubInformationPageData;
        } else {
            $newsListArray['total'] = $clubInformationPageData->count();
            $newsListArray['data'] = $clubInformationPageData->get();
        }

        $response = $newsListArray;
        return $response;
    }
}
