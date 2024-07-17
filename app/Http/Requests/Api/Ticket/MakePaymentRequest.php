<?php

namespace App\Http\Requests\api\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakePaymentRequest extends FormRequest
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
			'tickets' => 'required|json',
			'number_of_seats' => 'required',
			'match_id' => 'required',
			'final_amount' => 'required',
		];
	}
}
