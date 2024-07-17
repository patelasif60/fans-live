<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TokenCheckRequest extends FormRequest
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
            'token'    => 'required_if:provider,facebook,twitter,google',
            'provider' => 'required|in:facebook,twitter,google,apple',
            'user_identifier' => 'required_if:provider,apple'
        ];
    }
}
