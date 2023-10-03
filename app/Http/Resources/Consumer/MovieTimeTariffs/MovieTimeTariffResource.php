<?php


namespace App\Http\Resources\Consumer\MovieTimeTariffs;


use App\Enums\PromotionTypes;
use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieTimeTariffResource extends JsonResource
{
    protected $movieTime;

    public function toArray($request)
    {
        return [
            'id'                  => $this->resource instanceof TicketPromotion ? $this->movie_time_tariff_id : $this->id,
            'tariff_key'          => $this->isBirthday ? $this->type_tariff : $this->movie_tariff->remote_funtar,
            'name'                => $this->resource instanceof TicketPromotion ? $this->resource->name : $this->movie_tariff->name,
            'online_price'        => $this->ticket_promotion_id ? $this->price : $this->online_price,
            'tickets_number'      => $this->resource instanceof TicketPromotion ? $this->resource->ticket_qty : 1,
            'tickets_number_max'  => $this->resource instanceof TicketPromotion ? $this->resource->tickets_max : null,
            'ticket_promotion_id' => $this->ticket_promotion_id ? $this->ticket_promotion_id : null,
            'is_promotion'        => !!$this->ticket_promotion_id,
            'type'                => $this->ticket_promotion_id ? PromotionTypes::PROMOCION : PromotionTypes::NORMAL,
        ];
    }
}
