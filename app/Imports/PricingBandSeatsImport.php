<?php

namespace App\Imports;

use App\Models\PricingBandSeat;
use App\Models\StadiumBlock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PricingBandSeatsImport implements ToModel, WithHeadingRow
{
	/**
     * @var clubId
     */
    protected $clubId;

    /**
     * @var pricingBandId
     */
    protected $pricingBandId;

    /**
     * Create a new instance.
     */
    public function __construct($clubId, $pricingBandId)
    {
    	$this->clubId = $clubId;
        $this->pricingBandId = $pricingBandId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $stadiumBlockId = StadiumBlock::where('club_id', $this->clubId)->where('name', $row['block'])->first()->id;

        return new PricingBandSeat([
            'stadium_block_id'  => $stadiumBlockId,
            'pricing_band_id'   => $this->pricingBandId,
            'row'               => $row['row'],
            'seat_from'         => $row['seat_from'],
            'seat_to'           => $row['seat_to'],
        ]);
    }
}
