<?php

namespace App\Http\Requests\api\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class SellMatchTicketRequest extends FormRequest
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
            'booked_ticket_id'      => 'required',
            'return_time_to_wallet' => 'required',
            'account_number'        => 'required',
            'sort_code'             => 'required',
        ];
    }
}
