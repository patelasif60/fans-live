<?php

namespace App\Repositories;

use App\Models\TravelOffer;
use Carbon\Carbon;
use DB;

class TravelOfferRepository extends BaseRepository
{
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
        $travelOffersData = DB::table('travel_offers')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'travel_offers.id';
            $sorttype = 'desc';
        }
        $travelOffersData = $travelOffersData->orderBy($sortby, $sorttype);

        if (isset($data['name']) && trim($data['name']) != '') {
            $travelOffersData->where('travel_offers.title', 'like', '%'.$data['name'].'%');
        }

        if (!empty($data['from_date'])) {
            $travelOffersData->whereDate('travel_offers.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }
        if (!empty($data['to_date'])) {
            $travelOffersData->whereDate('travel_offers.show_until', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }
        $travelOffersListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $travelOffersData = $travelOffersData->paginate($data['pagination_length']);
            $travelOffersListArray = $travelOffersData;
        } else {
            $travelOffersListArray['total'] = $travelOffersData->count();
            $travelOffersListArray['data'] = $travelOffersData->get();
        }

        $response = $travelOffersListArray;

        return $response;
    }

    /**
     * Handle logic to create a TravelOffers.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $travelOffers = TravelOffer::create([
            'club_id'            => $clubId,
            'title'              => $data['name'],
            'content'            => $data['content_description'],
            'thumbnail'          => $data['thumbnail'],
            'thumbnail_file_name'=> $data['thumbnail_file_name'],
            'banner'             => $data['banner'],
            'banner_file_name'   => $data['banner_file_name'],
            'icon'               => $data['icon'],
            'icon_file_name'     => $data['icon_file_name'],
            'button_colour'      => $data['button_colour'],
            'button_text_colour' => $data['button_text_colour'],
            'button_text'        => $data['button_text'],
            'button_url'         => $data['button_url'],
            'status'             => $data['status'],
            'publication_date'   => convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'show_until'         => convertDateTimezone($data['showuntil'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'         => $user->id,
            'updated_by'         => $user->id,
        ]);

        return $travelOffers;
    }

    /**
     * Handle logic to update a TravelOffers.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $travelOffers, $data)
    {
        $travelOffers->fill([
            'title'              => $data['name'],
            'content'            => $data['content_description'],
            'thumbnail'          => $data['thumbnail'],
            'thumbnail_file_name'=> $data['thumbnail_file_name'],
            'banner'             => $data['banner'],
            'banner_file_name'   => $data['banner_file_name'],
            'icon'               => $data['icon'],
            'icon_file_name'     => $data['icon_file_name'],
            'button_colour'      => $data['button_colour'],
            'button_text_colour' => $data['button_text_colour'],
            'button_text'        => $data['button_text'],
            'button_url'         => $data['button_url'],
            'status'             => $data['status'],
            'publication_date'   => convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'show_until'         => convertDateTimezone($data['showuntil'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'         => $user->id,
            'updated_by'         => $user->id,
        ]);
        $travelOffers->save();

        return $travelOffers;
    }

    /**
     * Handle logic to get a club  TravelOffers.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getSwipeActionItems($clubId)
    {
        return TravelOffer::where('club_id', $clubId)->get();
    }
}
