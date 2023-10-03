<?php


namespace App\Http\Resources\TicketPromotions;


use App\Enums\TradeName;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketPromotionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                       => $this->id,
            'code'                     => $this->code,
            'name'                     => $this->name,
            'discount_rate'            => $this->discount_rate,
            'headquarter_name'         => $this->headquarter ? $this->headquarter->name : 'Todas',
            'movie_chain_name'         => $this->movie_chain ? ($this->movie_chain == '1' ? TradeName::CINESTAR : TradeName::MOVIETIME) : 'Todas',
            'price_second_ticket'      => $this->price_second_ticket,
            'price_ticket'             => $this->price_ticket,
            'price_product'            => $this->price_product,
            'start_date'               => $this->start_date->format('d/m/Y'),
            'end_date'                 => $this->end_date->format('d/m/Y'),
            'valid'                    => $this->end_date >= now(),
            'tickets_number'           => $this->tickets_number,
            'product'                  => $this->product,
            'membership_card_required' => $this->membership_card_required == true,
            'is_block_3d'              => $this->is_block_3d == true,
            'is_block_1s'              => $this->is_block_1s == true,
            'is_block_sunday'          => $this->is_block_sunday == true,
            'is_block_monday'          => $this->is_block_monday == true,
            'is_block_tuesday'         => $this->is_block_tuesday == true,
            'is_block_wednesday'       => $this->is_block_wednesday == true,
            'is_block_thursday'        => $this->is_block_thursday == true,
            'is_block_friday'          => $this->is_block_friday == true,
            'is_block_saturday'        => $this->is_block_saturday == true,
            'promo_tickets_number'     => $this->promo_tickets_number,
            'movie_tariff_name'        => $this->movie_tariff ? $this->movie_tariff->name : $this->tariff_type,
            'payment_method_type_name' => $this->payment_method_type ? $this->payment_method_type->name : 'Todas',
            'max_num_tickets'          => $this->max_num_tickets,
            'promotion_type'           => $this->promotion_type,
            'is_award'                 => $this->award ? true : false,
            'type'                     => $this->award ? 'PREMIO' : 'PROMO',
            'description'              => $this->description,
            'image'                    => $this->image_path,
        ];
    }
}
