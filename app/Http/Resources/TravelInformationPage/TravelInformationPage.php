<?php

namespace App\Http\Resources\TravelInformationPage;

use App\Http\Resources\TravelInformationPageContent\TravelInformationPageContent as TravelInformationPageContentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelInformationPage extends JsonResource
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
            'id'               => $this->id,
            'title'            => $this->title,
            'photo'            => $this->photo,
            'icon'             => $this->icon,
            'publication_date' => $this->publication_date,
            'content'          => TravelInformationPageContentResource::collection($this->travelInformationPageContent),
        ];
    }
}
