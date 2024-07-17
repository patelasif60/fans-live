<?php

namespace App\Imports;

use App\Models\StadiumBlock;
use App\Models\TicketingUnavailableSeat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TicketingUnavailableSeatsImport implements ToModel, WithHeadingRow
{
    /**
     * @var matchTicketingId
     */
    protected $matchTicketingId;

    /**
     * Create a new instance.
     */
    public function __construct($matchTicketingId)
    {
        $this->matchTicketingId = $matchTicketingId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $stadiumBlockId = StadiumBlock::where('name', $row['block'])->first()->id;

        return new TicketingUnavailableSeat([
            'match_ticketing_id'   => $this->matchTicketingId,
            'stadium_block_id'     => $stadiumBlockId,
            'row'                  => $row['row'],
            'seat_from'            => $row['seat_from'],
            'seat_to'              => $row['seat_to'],
        ]);
    }
}
