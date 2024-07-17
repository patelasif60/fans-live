<?php

namespace App\Http\Requests\User\CMS;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:users,email',
            'company'    => 'required',
            'status'     => 'required',
        ];
        if ($this->request->get('role') != 'superadmin') {
            $rules['club'] = 'required';
        }

        return $rules;
    }
}
