<?php

namespace App\Http\Requests\StadiumEntrance;

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
            'entrances_list.*.name'      => 'required',
            'entrances_list.*.latitude'  => 'required',
            'entrances_list.*.longitude' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'entrances_list.*.name.required'      => 'This field is required',
            'entrances_list.*.latitude.required'  => 'This field is required',
            'entrances_list.*.longitude.required' => 'This field is required',
        ];
    }
}
