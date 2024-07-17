<?php

namespace App\Http\Resources\Competition;

use Illuminate\Http\Resources\Json\JsonResource;

class Competition extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
            'logo'   => $this->logo,
            'status' => $this->status,
        ];
    }
}
