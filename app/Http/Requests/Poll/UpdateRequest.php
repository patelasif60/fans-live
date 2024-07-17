<?php

namespace App\Http\Requests\Poll;

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
        $dateTimeCmsFormat = config('fanslive.DATE_TIME_CMS_FORMAT.php');
        return [
            'title'            => 'required',
            'question'         => 'required',
            'associated_match' => 'required',
            'publication_date' => 'required|date_format:'.$dateTimeCmsFormat.'|before_or_equal:display_results_date',
            'display_results_date'     => 'required|date_format:'.$dateTimeCmsFormat.'|after_or_equal:publication_date',
            'answers'          => 'required|array',
            'answers.*.answer' => 'required',
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
            'answers.*.answer.required' => 'This field is required',
        ];
    }
}
