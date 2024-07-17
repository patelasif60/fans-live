<?php

namespace App\Http\Requests\Api\MembershipPackage;

use Illuminate\Foundation\Http\FormRequest;

class MembershipPackagePaymentRequest extends FormRequest
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
            'checkout_id'                    => 'required',
            'consumer_membership_package_id' => 'required',
        ];
    }
}
