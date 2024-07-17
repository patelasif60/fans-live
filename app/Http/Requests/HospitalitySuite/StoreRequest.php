<?php

namespace App\Http\Requests\HospitalitySuite;

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
        return [
            'name'              => 'required',
            'pubdate'           => 'required',
            'button_colour'     => 'required',
            'button_text_colour'=> 'required',
            'button_text'       => 'required',
            'button_url'        => 'required',
            'showuntil'         => 'required',
            'status'            => 'required',
        ];
    }
}
