<?php

namespace App\Http\Requests\Api\Consumer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JWTAuth;

class UpdateRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore(JWTAuth::user()->id),
            ],
            'date_of_birth'  => 'required',
            'receive_offers' => 'required',
            'timezone'       => 'required',
        ];
    }
}
