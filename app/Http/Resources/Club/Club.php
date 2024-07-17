<?php

namespace App\Http\Resources\Club;

use App\Http\Resources\Competition\Competition as CompetitionResource;
use App\Http\Resources\StadiumGeneralSetting\StadiumGeneralSetting as StadiumGeneralSettingResource;
use App\Http\Resources\StadiumBlock\StadiumBlock as StadiumBlockResource;
use App\Http\Resources\StadiumEntrance\StadiumEntrance as StadiumEntranceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Club extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $primaryCompetition = $this->getPrimaryCompetition();

        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'logo'                => $this->logo,
            'primary_colour'      => $this->primary_colour,
            'secondary_colour'    => $this->secondary_colour,
            'timezone'            => $this->time_zone,
            'currency'            => $this->currency,
            'currency_symbol'     => $this->currency === 'GBP' ? '&#163;' : '&#128;',
            'created_by'          => $this->creator,
            'updated_by'          => $this->updater,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
            'category'            => $this->category,
            'primary_competition' => $primaryCompetition ? new CompetitionResource($primaryCompetition) : null,
            'stadium'             => new StadiumGeneralSettingResource($this->stadium),
            'stadium_blocks'      => StadiumBlockResource::collection($this->stadiumBlocks),
            'stadium_entrances'   => StadiumEntranceResource::collection($this->stadiumEntrances),
        ];
    }
}
