<?php


namespace App\Http\Resources\ChocoPromotions;


use App\Enums\TradeName;
use Illuminate\Http\Resources\Json\JsonResource;

class ChocoPromotionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                       => $this->id,
            'code'                     => $this->code,
            'name'                     => $this->name,
            'start_date'               => $this->start_date->format('d/m/Y'),
            'end_date'                 => $this->end_date->format('d/m/Y'),
            'discount_rate'            => $this->discount_rate,
            'membership_card_required' => $this->membership_card_required == true,
            'payment_method_type_name' => $this->payment_method_type ? $this->payment_method_type->name : 'Todas',
            'headquarter_name'         => $this->headquarter ? $this->headquarter->name : 'Todas',
            'movie_chain_name'         => $this->movie_chain ? ($this->movie_chain == '1' ? TradeName::CINESTAR : TradeName::MOVIETIME) : 'Todas',
            'applies_to_all'           => $this->applies_to_all == true,
            'products'                 => $this->products,
            'valid'                    => $this->end_date >= now(),
            'is_block_sunday'          => $this->is_block_sunday == true,
            'is_block_monday'          => $this->is_block_monday == true,
            'is_block_tuesday'         => $this->is_block_tuesday == true,
            'is_block_wednesday'       => $this->is_block_wednesday == true,
            'is_block_thursday'        => $this->is_block_thursday == true,
            'is_block_friday'          => $this->is_block_friday == true,
            'is_block_saturday'        => $this->is_block_saturday == true,
            'description'              => $this->description,
            'image'                    => $this->image_path,
        ];
    }
}
