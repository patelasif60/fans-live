<?php

namespace App\Http\Resources\MatchHospitality;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HospitalitySuite\HospitalitySuite as HospitalitySuiteResource;

class MatchHospitality extends JsonResource
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
    }
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $hospitalitySuitesCount = $this->hospitalitySuites()->count();
        return [
            'id'                          => $this->id,
            'rewards_percentage_override' => $this->rewards_percentage_override,
            'hospitality_suite_count' => $hospitalitySuitesCount,
            'hospitality_suite_detail' => $hospitalitySuitesCount === 1 ? new HospitalitySuiteResource($this->hospitalitySuites()->first(), $this->matchId, $this->consumer, $this->accessFrom) : null,
        ];
    }
}
