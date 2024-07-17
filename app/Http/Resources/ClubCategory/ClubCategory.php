<?php

namespace App\Http\Resources\ClubCategory;

use App\Http\Resources\Club\Club as ClubResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubCategory extends JsonResource
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
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'logo'       => $this->logo,
            'clubs'      => ClubResource::collection($this->publishedClubs),
            'created_by' => $this->creator,
            'updated_by' => $this->updater,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
