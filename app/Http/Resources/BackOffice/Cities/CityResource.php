<?php


namespace App\Http\Resources\BackOffice\Cities;


use App\Enums\TradeName;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'trade_name'         => $this->trade_name,
            'trade_display_name' => TradeName::getNameByTrade($this->trade_name),
        ];
    }

}
