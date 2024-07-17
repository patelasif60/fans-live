<?php

namespace App\Repositories;

use App\Models\StadiumGeneralSetting;

/**
 * Repository class for User model.
 */
class StadiumGeneralSettingRepository extends BaseRepository
{
    /**
     * Handle logic to update a stadium general setting.
     *
     * @param $user
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $clubId, $data)
    {
        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();

        if ($stadiumGeneralSetting) {
            $stadiumGeneralSetting->fill([
                'club_id'                                 => $clubId,
                'name'                                    => $data['name'],
                'address'                                 => $data['address'],
                'address_2'                               => $data['address_2'],
                'town'                                    => $data['town'],
                'postcode'                                => $data['postcode'],
                'latitude'                                => isset($data['latitude']) ? $data['latitude'] : null,
                'longitude'                               => isset($data['longitude']) ? $data['longitude'] : null,
                'is_using_allocated_seating'              => isset($data['is_using_allocated_seating']) ? $data['is_using_allocated_seating'] : 0,
                'aerial_view_ticketing_graphic'           => $data['aerial_view_ticketing_graphic'],
                'aerial_view_ticketing_graphic_file_name' => $data['aerial_view_ticketing_graphic_file_name'],
                'image'           						  => $data['image'],
                'image_file_name' 						  => $data['image_file_name'],
                'number_of_seats'                         => isset($data['number_of_seats']) ? $data['number_of_seats'] : null,
                'created_by'                              => $user->id,
                'updated_by'                              => $user->id,
            ]);
        } else {
            $stadiumGeneralSetting = StadiumGeneralSetting::create([
                'club_id'                                 => $clubId,
                'name'                                    => $data['name'],
                'address'                                 => $data['address'],
                'address_2'                               => $data['address_2'],
                'town'                                    => $data['town'],
                'postcode'                                => $data['postcode'],
                'latitude'                                => isset($data['latitude']) ? $data['latitude'] : null,
                'longitude'                               => isset($data['longitude']) ? $data['longitude'] : null,
                'is_using_allocated_seating'              => isset($data['is_using_allocated_seating']) ? $data['is_using_allocated_seating'] : 0,
                'aerial_view_ticketing_graphic'           => $data['aerial_view_ticketing_graphic'],
                'aerial_view_ticketing_graphic_file_name' => $data['aerial_view_ticketing_graphic_file_name'],
				'image'           						  => $data['image'],
				'image_file_name' 						  => $data['image_file_name'],
                'number_of_seats'                         => isset($data['number_of_seats']) ? $data['number_of_seats'] : null,
                'created_by'                              => $user->id,
                'updated_by'                              => $user->id,
            ]);
        }
        $stadiumGeneralSetting->save();

        return $stadiumGeneralSetting;
    }

    public function updateLatLong($request)
    {
        StadiumGeneralSetting::find($request['id'])->update(['latitude' => $request['latitude'], 'longitude' => $request['logitude']]);
    }
}
