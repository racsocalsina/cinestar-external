<?php


namespace App\Http\Resources\API\TicketAward;


use App\Enums\PromotionTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketAwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'points'         => $this->points,
            'tickets_number' => $this->promotion->tickets_number,
            'online_price' => $this->price,
            'movie_time_tariff_id' => $this->movie_time_tariff_id,
            'type' => PromotionTypes::PREMIO
        ];
    }
}
