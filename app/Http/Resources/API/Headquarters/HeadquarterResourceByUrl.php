<?php


namespace App\Http\Resources\API\Headquarters;

use App\Enums\BusinessName;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterResourceByUrl extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'point_sale'    => $this->point_sale,
            'business_id'   => BusinessName::getValueByBusinessName($this->business_name),
            'business_name' => $this->business_name,
            'trade_name'    => $this->trade_name,
        ];
    }

}
