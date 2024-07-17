<?php

namespace App\Http\Resources\api\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;

class SellMatchTicket extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
