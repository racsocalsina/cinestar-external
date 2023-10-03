<?php


namespace App\Http\Resources\API\ChocoAward;


use App\Enums\PromotionTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class ChocoAwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->product->id,
            'name' => $this->name,
            'points' => $this->points,
            'image' => $this->product->image_path ? $this->product->image_path : asset('assets/img/no-product.png'),
            'price' => 0,
            'favorite' => false,
            'sweet_type' => $this->product->is_combo ? 'combo' : 'product',
            'promotion_id' => $this->id,
            'type' => PromotionTypes::PREMIO,
        ];
    }
}
