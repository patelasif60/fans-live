<?php

namespace App\Http\Resources\ClubModuleSetting;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Modules\Modules as ModulesResource;

class ClubModuleSetting extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'module' => new ModulesResource($this->modules),
            'is_active' => $this->is_active,
            'created_by_first_name' => $this->creator->first_name,
            'created_by_last_name' => $this->creator->last_name,
            'created_by_email' => $this->creator->email,
            'created_by_id' => $this->creator->id,
            'updated_by_first_name' => $this->updater->first_name,
            'updated_by_last_name' => $this->updater->last_name,
            'updated_by_email' => $this->updater->email,
            'updated_by_id' => $this->updater->id,
        ];
    }
}
