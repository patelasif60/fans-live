<?php

namespace App\Http\Resources\TicketTransaction;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketTransactionCollection extends ResourceCollection
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
            'data' => $this->collection,
        ];
    }
}
