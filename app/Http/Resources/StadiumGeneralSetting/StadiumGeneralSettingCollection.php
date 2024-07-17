<?php

namespace App\Http\Resources\StadiumGeneralSetting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StadiumGeneralSettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
