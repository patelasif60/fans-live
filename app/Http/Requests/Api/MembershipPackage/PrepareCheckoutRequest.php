<?php

namespace App\Http\Requests\Api\MembershipPackage;

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
            'membership_package_id' => 'required',
            'consumer_card_id'      => 'required',
        ];
    }
}
