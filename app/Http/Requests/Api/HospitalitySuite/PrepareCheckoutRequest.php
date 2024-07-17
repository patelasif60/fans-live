<?php

namespace App\Http\Requests\Api\HospitalitySuite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrepareCheckoutRequest extends FormRequest
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
            'consumer_card_id' => 'required',
            'hospitality_suite_dietary_options' => 'required|json',
            'number_of_seats' => 'required',
            'hospitality_suits_id' => 'required',
        ];
    }
}
