<?php

namespace App\Http\Requests\api\HospitalitySuite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakePaymentRequest extends FormRequest
{/**
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
			'hospitality_suite_dietary_options' => 'required|json',
			'number_of_seats' => 'required',
			'hospitality_suits_id' => 'required',
			'final_amount' => 'required',
		];
	}
}
