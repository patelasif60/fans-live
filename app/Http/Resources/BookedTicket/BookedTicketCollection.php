<?php

namespace App\Http\Resources\BookedTicket;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\BookedTicket\BookedTicket as BookedTicketResource;

class BookedTicketCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    protected $checkWalletDetails;
    /**
     * Set wallet details flag.
     *
     * @param  $checkWalletDetails
     *
     * @return int
     */
    public function checkWalletDetailsFlag($checkWalletDetails = false)
    {
        $this->checkWalletDetails = $checkWalletDetails;

        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function (BookedTicketResource $resource) use ($request) {
            return $resource->checkWalletDetailsFlag($this->checkWalletDetails)->toArray($request);
        })->all();
    }
}
