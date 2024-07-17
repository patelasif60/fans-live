<?php


namespace App\Http\Requests\Api\Ticket;


use Illuminate\Foundation\Http\FormRequest;

class ScanTicketRequest extends FormRequest
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
			'ticket_id' => 'required',
			'type' 		=> 'required'
		];
	}

}
