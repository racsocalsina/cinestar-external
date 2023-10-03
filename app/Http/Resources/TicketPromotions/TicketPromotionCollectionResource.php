<?php


namespace App\Http\Resources\TicketPromotions;


use App\Enums\TradeName;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketPromotionCollectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'tickets_number' => $this->tickets_number,
            'type' => $this->award ? 'PREMIO' : 'PROMO'
        ];
    }
}
