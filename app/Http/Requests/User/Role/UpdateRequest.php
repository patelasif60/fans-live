<?php

namespace App\Http\Requests\User\Role;

use Illuminate\Foundation\Http\FormRequest;

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
            'display_name' => 'required',
            'permission'   => 'required',
        ];
    }

    /**
     * Get the validation massage that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'permission.required' => 'At least select one permission.',
        ];
    }
}