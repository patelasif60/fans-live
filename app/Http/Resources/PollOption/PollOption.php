<?php

namespace App\Http\Resources\PollOption;

use Illuminate\Http\Resources\Json\JsonResource;

class PollOption extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The optionCount instance.
     *
     * @var int
     */
    public $optionCount;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct($resource, $optionCount)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->optionCount = $optionCount;
    }

    /**
     * Total options count.
     *
     * @param  $totalOptionsCount
     *
     * @return int
     */
    public function totalOptionsCount($totalOptionsCount)
    {
        $this->optionCount = $totalOptionsCount;

        return $this;
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
        return [
            'id'               => $this->id,
            'text'             => $this->text,
            'count'            => $this->count,
            'count_in_percent' => $this->optionCount > 0 ? $this->count * 100 / $this->optionCount : 0.0,
        ];
    }
}
