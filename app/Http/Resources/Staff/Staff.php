<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\Club\Club as ClubResource;

class Staff extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'user'           => new UserResource($this->user),
            'club'           => new ClubResource($this->club),
        ];
    }
}
