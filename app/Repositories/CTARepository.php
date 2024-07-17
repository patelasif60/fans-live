<?php

namespace App\Repositories;

use App\Models\CTA;
use Carbon\Carbon;
use DB;

/**
 * Repository class for CTA model.
 */
class CTARepository extends BaseRepository
{
    /**
     * Handle logic to create a new CTA.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $cta = CTA::create([
            'club_id'          => $clubId,
            'title'            => $data['title'],
            'image'            => $data['image'],
            'image_file_name'  => $data['image_file_name'],
            'button1_text'     => $data['first_button_text'],
            'button1_action'   => $data['first_button_action'],
            'button1_item'     => isset($data['first_button_item'])?$data['first_button_item']:null,
            'button2_text'     => $data['second_button_text'],
            'button2_action'   => $data['second_button_action'],
            'button2_item'     => isset($data['second_button_item'])?$data['second_button_item']:null,
            'status'           => $data['status'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);

        return $cta;
    }

    /**
     * Handle logic to update a CTA.
     *
     * @param $user
     * @param $cta
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $cta, $data)
    {
        $cta->fill([
            'title'            => $data['title'],
            'image'            => $data['image'],
            'image_file_name'  => $data['image_file_name'],
            'button1_text'     => $data['first_button_text'],
            'button1_action'   => $data['first_button_action'],
            'button1_item'     => isset($data['first_button_item'])?$data['first_button_item']:null,
            'button2_text'     => $data['second_button_text'],
            'button2_action'   => $data['second_button_action'],
            'button2_item'     => isset($data['second_button_item'])?$data['second_button_item']:null,
            'status'           => $data['status'],
            'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);
        $cta->save();

        return $cta;
    }

    /**
     * Get cta data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $ctaData = DB::table('ctas')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'ctas.id';
            $sorttype = 'desc';
        }
        $ctaData = $ctaData->orderBy($sortby, $sorttype);

        if (isset($data['text']) && trim($data['text']) != '') {
            $ctaData->where('ctas.title', 'like', '%'.$data['text'].'%');
        }

        if (!empty($data['from_date'])) {
            $ctaData->whereDate('ctas.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['to_date'])) {
            $ctaData->whereDate('ctas.publication_date', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $ctaListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $ctaData = $ctaData->paginate($data['pagination_length']);
            $ctaListArray = $ctaData;
        } else {
            $ctaListArray['total'] = $ctaData->count();
            $ctaListArray['data'] = $ctaData->get();
        }

        $response = $ctaListArray;

        return $response;
    }
}
