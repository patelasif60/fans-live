<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrepareCheckoutRequest extends FormRequest
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
            'consumer_card_id' => 'required',
            'type' => 'required',
            'selected_collection_time' => 'required',
            'products' => 'required|json',
        ];
    }
}
