<?php


namespace App\Http\Requests\setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
			'minimum_card_fee_amount' => [
				'required',
				'numeric',
			],
			'bank_fee' => [
				'required',
				'numeric',
			],
			'card_fee_percentage' => [
				'required',
				'numeric',
				'between:0,100',
			],
			'footer_text_for_receipt' => [
				'required',
			],
			'max_transaction_amount' => [
				'required',
			],
			'threshold_transaction_minutes' => [
				'required',
			]
		];
	}
}
