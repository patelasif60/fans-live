<?php

namespace App\Imports;

use App\Models\StadiumBlockSeat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StadiumBlockSeatsUpdate implements ToCollection
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
        $seats = StadiumBlockSeat::where('stadium_block_id', $this->stadiumBlockId)->get();

        $data = [];
        $i = 0;
        foreach ($rows as $row) {
            foreach ($firstRow as $key => $val) {
                if (in_array($row[$key + 1], config('fanslive.STADIUM_BLOCK_TYPE'))) {
                    $data[$i]['stadium_block_id'] = $this->stadiumBlockId;
                    $data[$i]['row'] = $row[0];
                    $data[$i]['seat'] = $val;
                    $data[$i]['type'] = $row[$key + 1];
                    
                    $seatData = $seats->where('row', $row[0])->where('seat', $val)->first();
                    if(isset($seatData)) {
                        if($seatData->type != $row[$key + 1]) {
                            $seatData->type = $row[$key + 1];
                            $seatData->save();
                        }
                    } else {
                        $stadiumBlockSeat = new StadiumBlockSeat();
                        $stadiumBlockSeat->stadium_block_id = $this->stadiumBlockId;
                        $stadiumBlockSeat->row = $row[0];
                        $stadiumBlockSeat->seat = $val;
                        $stadiumBlockSeat->type = $row[$key + 1];
                        $stadiumBlockSeat->save();
                    }
                    $i++;
                }
            }
        }

        $collectionData = collect($data);
        foreach ($seats as $key => $value) {
            $check = $collectionData->where('row', $value->row)->where('seat', $value->seat)->count();
            if($check == 0) {
                $value->delete();
            }
        }
    }
}
