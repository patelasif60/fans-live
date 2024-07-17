<?php

namespace App\Http\Resources\MatchTicketingSponsor;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchTicketingSponsor extends JsonResource
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
            'id'             => $this->id,
            'logo'           => $this->logo,
            'logo_file_name' => $this->logo_file_name,
        ];
    }
}
