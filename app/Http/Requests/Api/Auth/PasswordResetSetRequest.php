<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordResetSetRequest extends FormRequest
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
            'token'    => 'required',
            'password' => 'required|confirmed|min:8',
            'email'    => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) {
                    $query->where('provider', 'email');
                }),
            ],
        ];
    }

    /**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
	    return [
	        'email.exists' => 'This is not a registered email address',
	    ];
	}

}
