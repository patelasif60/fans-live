<?php

namespace App\Http\Resources\Modules;

use Illuminate\Http\Resources\Json\JsonResource;

class Modules extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'key' => $this->key,
        ];
    }
}