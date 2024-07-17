<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required_if:provider,email|min:8',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'receive_offers' => 'required',
            'date_of_birth'  => 'required',
            'timezone'       => 'required',
            'provider'       => 'required',
            'provider_id'    => 'required_if:provider,google,facebook',
        ];
    }
}
