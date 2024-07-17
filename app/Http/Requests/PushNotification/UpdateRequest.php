<?php

namespace App\Http\Requests\PushNotification;

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
        $rules = [
            'message'                           => 'required',
            'publication_date'                  => 'required|date_format:'.$dateTimeCmsFormat,
            'swipe_action_category'             => 'required',
            'send_to_user_attending_this_match' => 'required',
        ];
        if ($this->request->get('swipe_action_category') == 'merchandise_category' || $this->request->get('swipe_action_category') == 'food_and_drink_category' || $this->request->get('swipe_action_category') == 'travel_offer') {
            $rules['swipe_action_item'] = 'required';
        }
        return $rules;
    }
}
