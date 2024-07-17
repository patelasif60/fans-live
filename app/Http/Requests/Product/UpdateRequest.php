<?php

namespace App\Http\Requests\Product;

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
            'title'            => 'required',
            'status'           => 'required',
            'short_description'=> 'required',
            'description'      => 'required',
            'price'            => 'required',
            'vat_rate'         => 'required',
        ];
    }
}
