<?php

namespace App\Http\Resources\ClubInformationPage;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClubInformationContentSection\ClubInformationContentSection as ClubInformationContentSectionResource;


class ClubInformationPage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'publication_date' => $this->publication_date,
            'content' => ClubInformationContentSectionResource::collection($this->clubInformationPageContent),
        ];
    }
}
