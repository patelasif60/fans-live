<?php

namespace App\Http\Resources\QuizMultipleChoiceQuestionAnswer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuizMultipleChoiceQuestionAnswerCollection extends ResourceCollection
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