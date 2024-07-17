<?php

namespace App\Imports;

use App\Models\HospitalityUnavailableSeat;
use App\Models\StadiumBlock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HospitalityUnavailableSeatsImport implements ToModel, WithHeadingRow
{
    /**
     * @var matchHospitalityId
     */
    protected $matchHospitalityId;

    /**
     * Create a new instance.
     */
    public function __construct($matchHospitalityId)
    {
        $this->matchHospitalityId = $matchHospitalityId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $stadiumBlockId = StadiumBlock::where('name', $row['block'])->first()->id;

        return new HospitalityUnavailableSeat([
            'match_hospitality_id'   => $this->matchHospitalityId,
            'stadium_block_id'       => $stadiumBlockId,
            'row'                    => $row['row'],
            'seat_from'              => $row['seat_from'],
            'seat_to'                => $row['seat_to'],
        ]);
    }
}
