<?php

namespace App\Http\Resources\Event;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The consumer instance.
     *
     * @var mixed
     */
    public $consumer;

    /**
     * The accessFrom instance.
     *
     * @var mixed
     */
    public $accessFrom;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct($resource, $consumer = NULL, $accessFrom = NULL)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->consumer = $consumer;
        $this->accessFrom = $accessFrom;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isAccessible = NULL;
        if ($this->accessFrom == 'console') {
            $isAccessible = $this->eventMembershipPackageAccessByConsumer($this->consumer);
        } else {
            $isAccessible = $this->eventMembershipPackageAccess();
        }
        return [
            'id'                          => $this->id,
            'title'                       => $this->title,
            'description'                 => $this->description,
            'location'                    => $this->location,
            'date_time'                   => $this->date_time,
            'image'                       => $this->image,
            'rewards_percentage_override' => (int) $this->rewards_percentage_override,
            'price'                       => formatNumber($this->price),
            'final_price'                 => formatNumber($this->price + (($this->price * $this->vat_rate) / 100)),
            'number_of_tickets_available' => $this->number_of_tickets - $this->getEventTickets($this->resource),
            'number_of_seats'             => $this->number_of_tickets,
            'status'                      => $this->status,
            'is_accessible'               => $isAccessible,
            'accessible_for'              => $this->membershipPackage()->pluck('title')->toArray(),
        ];
    }
}
