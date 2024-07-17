<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
			'club_id' => $this->club_id,
			'title' => $this->title,
			'type' => $this->type,
			'image' => $this->image,
			'image_file_name' => $this->image_file_name,
			'rewards_percentage_override' => $this->rewards_percentage_override,
			'status' => $this->status,
			'is_restricted_to_over_age' => $this->is_restricted_to_over_age,
			'related_to' => 'product'
		];
	}
}
