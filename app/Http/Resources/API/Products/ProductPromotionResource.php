<?php


namespace App\Http\Resources\API\Products;


use App\Enums\PromotionTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPromotionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->product->id,
            'name'               => "{$this->choco_promotion->name}: {$this->product->name}",
            'image'              => $this->product->image_path ? $this->product->image_path : asset('assets/img/no-product.png'),
            'price'              => $this->price,
            'favorite'           => false,
            'type_id'            => $this->product->product_type_id,
            'sweet_type'         => $this->product->is_combo ? 'combo' : 'product',
            'choco_promotion_id' => $this->id,
            'type'               => PromotionTypes::PROMOCION,
        ];
    }
}
