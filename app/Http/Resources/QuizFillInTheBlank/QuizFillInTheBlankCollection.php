<?php

namespace App\Http\Resources\QuizFillInTheBlank;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuizFillInTheBlankCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'data' => $this->collection,
		];
	}
}