<?php

namespace App\Http\Requests\TravelWarning;

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
        $dateTimeCmsFormat = config('fanslive.DATE_TIME_CMS_FORMAT.php');
        return [
            'text'                      => 'required',
            'publication_date_time'     => 'required|date_format:'.$dateTimeCmsFormat.'|before_or_equal:show_until',
            'show_until'                => 'required|date_format:'.$dateTimeCmsFormat.'|after_or_equal:publication_date_time',
            'color'                     => 'required',
        ];
    }
}
