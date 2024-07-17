<?php

namespace App\Http\Requests\Api\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class SaveNotificationRequest extends FormRequest
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
            'match_id' => 'required',
            'reason'   => 'required',
        ];
    }
}
