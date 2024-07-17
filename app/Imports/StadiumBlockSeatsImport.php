<?php

namespace App\Imports;

use App\Models\StadiumBlockSeat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StadiumBlockSeatsImport implements ToCollection
{
    /**
     * @var stadiumBlockId
     */
    protected $stadiumBlockId;

    /**
     * Create a new instance.
     */
    public function __construct($stadiumBlockId)
    {
        $this->stadiumBlockId = $stadiumBlockId;
    }

    public function collection(Collection $rows)
    {
        $firstRow = $rows->first();
        $firstRow->shift();
        $rows->shift();
        foreach ($rows as $row) {
            foreach ($firstRow as $key => $val) {
                if (in_array($row[$key + 1], config('fanslive.STADIUM_BLOCK_TYPE'))) {
                    StadiumBlockSeat::create([
                        'stadium_block_id' => $this->stadiumBlockId,
                        'row'              => $row[0],
                        'seat'             => $val,
                        'type'             => $row[$key + 1],
                    ]);
                }
            }
        }
    }
}
