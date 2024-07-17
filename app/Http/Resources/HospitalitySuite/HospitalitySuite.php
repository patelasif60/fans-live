<?php

namespace App\Http\Resources\HospitalitySuite;

use Carbon\Carbon;
use App\Http\Resources\HospitalityDietaryOptions\HospitalityDietaryOptions as HospitalityDietaryOptionsResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Consumer;
use App\Models\Match;
use App\Repositories\ConsumerRepository;
use App\Services\ConsumerService;

class HospitalitySuite extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $matchId;

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
    public function __construct($resource, $matchId=null, $consumer=null, $accessFrom=null)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->matchId = $matchId;
        $this->consumer = $consumer;
        $this->accessFrom = $accessFrom;
        $this->consumerService = new ConsumerService(new ConsumerRepository());
    }
    /**
     * Set match Id.
     *
     * @param  $totalOptionsCount
     *
     * @return int
     */
    public function setMatchId($matchId = null)
    {
        $this->matchId = $matchId;

        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->accessFrom == 'console') {
            $consumer = $this->consumer;
        } else {
            $consumer = getLoggedinConsumer();
        }
        if($this->matchId)
        {
            $ticketAvailibilityDetail = [];
            $match = Match::find($this->matchId);
            if ($match->status === 'scheduled' && $consumer)
            {
                $ticketAvailibilityDetail = $this->consumerService->checkForMembershipPackageForMatch($consumer, $match->hospitalityMembership);
            }
        }
		$result = [
			'id'                          => $this->id,
			'title'                       => $this->title,
			'price'                       => formatNumber($this->price),
			'final_price' 				  => formatNumber($this->price + (($this->price * $this->vat_rate) / 100)),
			'club_id'                     => $this->club_id,
			'short_description'           => $this->short_description,
			'long_description'            => $this->long_description,
			'image'                       => $this->image,
			'image_file_name'             => $this->image_file_name,
			'is_active'                   => $this->is_active,
			'dietary_options'      		  => HospitalityDietaryOptionsResource::collection($this->hospitalityDietaryOptions),
            'number_of_seats'             => $this->number_of_seat,
		];
		if ($this->matchId) {
            $result = array_merge($result, [
                'number_of_tickets_available' => $this->number_of_seat - $this->getHospitalitySuiteTickets($this->resource, $this->matchId)
            ]);
            if ($match->status === 'scheduled') {
                $result = array_merge($result, [
                    'is_ticket_available'             => isset($ticketAvailibilityDetail['is_ticket_available']) ? $ticketAvailibilityDetail['is_ticket_available'] : null,
                    'ticket_availability_button_text' => isset($ticketAvailibilityDetail['ticket_availability_button_text']) ? $ticketAvailibilityDetail['ticket_availability_button_text'] : null,
                    'ticket_unavailibility_reason'    => isset($ticketAvailibilityDetail['ticket_unavailibility_reason']) ? $ticketAvailibilityDetail['ticket_unavailibility_reason'] : null,
                    'ticket_availability_message'     => isset($ticketAvailibilityDetail['ticket_availability_message']) ? $ticketAvailibilityDetail['ticket_availability_message'] : null,
                   // 'is_match_button_disabled'        => $isMatchButtonDisabled,
                ]);
            }
        }
        return $result;
    }
}
