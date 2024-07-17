<?php

namespace App\Http\Resources\HospitalitySuite;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\HospitalitySuite\HospitalitySuite as HospitalitySuiteResource;

class HospitalitySuiteCollection extends ResourceCollection
{
	 /**
     * The resource that this resource collects.
     *
     * @var string
     */
    protected $matchId;

    /**
     * Set match Id.
     *
     * @param  $totalOptionsCount
     *
     * @return int
     */
    public function setMatchId($matchId=null)
    {
        $this->matchId = $matchId;

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
		return $this->collection->map(function (HospitalitySuiteResource $resource) use ($request) {
            return $resource->setMatchId($this->matchId)->toArray($request);
        })->all();
	}
}
