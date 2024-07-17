<?php

namespace App\Http\Requests\Contentfeed;

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
            'type' => 'required',
            'name' => 'required',
        ];
        if ($this->request->get('type') == 'RSS') {
            $rules['rss_url'] = 'required';
        } elseif ($this->request->get('type') == 'Youtube') {
            $rules['api_channel_id'] = 'required';
        } elseif ($this->request->get('type') == 'Twitter') {
            $rules['api_key'] = 'required';
            $rules['api_secret_key'] = 'required';
            $rules['api_token'] = 'required';
            $rules['api_token_secret_key'] = 'required';
        } elseif ($this->request->get('type') == 'Facebook') {
            $rules['api_app_id'] = 'required';
            $rules['api_secret_key'] = 'required';
            $rules['api_token'] = 'required';
        } else {
            $rules['api_token'] = 'required';
        }

        return $rules;
    }
}
