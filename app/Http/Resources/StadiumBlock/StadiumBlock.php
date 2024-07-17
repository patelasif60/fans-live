<?php

namespace App\Http\Resources\StadiumBlock;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\StadiumEntranceRepository;
use App\Http\Resources\StadiumEntrance\StadiumEntrance as StadiumEntrance;

class StadiumBlock extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'stadium_entrances' => StadiumEntrance::collection($this->stadiumEntrances)
        ];
    }
}
