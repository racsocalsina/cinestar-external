<?php


namespace App\Http\Resources\BackOffice\Settings;


use Illuminate\Http\Resources\Json\JsonResource;

class SettingErpSystemVarCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'headquarter_name' => $this->headquarter ? $this->headquarter->name : null,
            'config'           => $this->config,
        ];
    }
}
