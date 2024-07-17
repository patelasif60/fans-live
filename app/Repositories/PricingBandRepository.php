<?php

namespace App\Repositories;

use App\Models\PricingBand;
use App\Models\StadiumBlock;
use DB;

/**
 * Repository class for pricing band model.
 */
class PricingBandRepository extends BaseRepository
{
    /**
     * Handle logic to create a new pricing band.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $pricingBand = PricingBand::create([
            'club_id'        => $clubId,
            'display_name'   => $data['display_name'],
            'internal_name'  => $data['internal_name'],
            'price'          => $data['price'],
            'seat'           => $data['seat'],
            'seat_file_name' => $data['seat_file_name'],
            'vat_rate'       => $data['vat_Rate'],
            'is_active'      => isset($data['is_active']) ? $data['is_active'] : 0,
            'created_by'     => $user->id,
            'updated_by'     => $user->id,
        ]);

        return $pricingBand;
    }

    /**
     * Handle logic to update a pricing band.
     *
     * @param $user
     * @param $cta
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $pricingBand, $data)
    {
        $pricingBand->fill([
            'display_name'   => $data['display_name'],
            'internal_name'  => $data['internal_name'],
            'price'          => $data['price'],
            'is_active'      => isset($data['is_active']) ? $data['is_active'] : 0,
            'seat'           => $data['seat'],
            'seat_file_name' => $data['seat_file_name'],
            'vat_rate'       => $data['vat_Rate'],
            'created_by'     => $user->id,
            'updated_by'     => $user->id,
        ]);
        $pricingBand->save();

        return $pricingBand;
    }

    /**
     * Get pricing band user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $pricingBandData = DB::table('pricing_bands');
        $pricingBandData = $pricingBandData->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'pricing_bands.id';
            $sorttype = 'desc';
        }
        $pricingBandData = $pricingBandData->orderBy($sortby, $sorttype);

        $pricingBandListData = [];

        if (!array_key_exists('pagination', $data)) {
            $pricingBandData = $pricingBandData->paginate($data['pagination_length']);
            $pricingBandListData = $pricingBandData;
        } else {
            $pricingBandListData['total'] = $pricingBandData->count();
            $pricingBandListData['data'] = $pricingBandData->get();
        }

        $response = $pricingBandListData;

        return $response;
    }

    /**
     * Delete pricing band seat data.
     *
     * @param $pricingBand
     *
     * @return mixed
     */
    public function deletePricingBandSeat($pricingBand)
    {
        $pricingBandSeat = $pricingBand->pricingBandSeat;
        foreach ($pricingBandSeat as $key => $seat) {
            $seat->delete();
        }
    }
}
