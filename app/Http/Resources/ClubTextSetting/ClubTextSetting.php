<?php

namespace App\Http\Resources\ClubTextSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class ClubTextSetting extends JsonResource
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
            'id' => $this->id,
            'hospitality_post_purchase_text' => $this->hospitality_post_purchase_text,
            'hospitality_introduction_text' => $this->hospitality_introduction_text,
            'membership_packages_introduction_text' => $this->membership_packages_introduction_text,
            'created_by_first_name' => $this->creator->first_name,
            'created_by_last_name' => $this->creator->last_name,
            'created_by_email' => $this->creator->email,
            'created_by_id' => $this->creator->id,
            'updated_by_first_name' => $this->updater->first_name,
            'updated_by_last_name' => $this->updater->last_name,
            'updated_by_email' => $this->updater->email,
            'updated_by_id' => $this->updater->id,
        ];
    }
}
