<?php

namespace App\Http\Requests\api\product;

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
            'type' => 'required',
            'selected_collection_time' => 'required',
            'products' => 'required|json',
            'final_amount' => 'required',
        ];
	}
}
