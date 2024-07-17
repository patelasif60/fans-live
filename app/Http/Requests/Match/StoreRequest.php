<?php

namespace App\Http\Requests\Match;

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
            'home'         => 'required',
            'away'         => 'required',
            'kickoff_time' => 'required',
        ];
    }
}
