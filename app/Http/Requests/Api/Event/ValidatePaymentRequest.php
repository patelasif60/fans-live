<?php

namespace App\Http\Requests\api\event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidatePaymentRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		   // 'events' => 'required|json',
			'event_id' => 'required',
			'number_of_seats' => 'required',
			'final_amount' => 'required',
		];
	}
}
