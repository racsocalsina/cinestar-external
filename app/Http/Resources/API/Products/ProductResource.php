<?php


namespace App\Http\Resources\API\Products;


use App\Enums\PromotionTypes;
use App\Enums\TradeName;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'image'      => $this->getImage(),
            'price'      => floatval($this->price),
            'favorite'   => !is_null($this->favorite_id),
            'type_id'    => $this->type_id,
            'sweet_type' => $this->is_combo ? 'combo' : 'product',
            'choco_promotion_id' => null,
            'type' => PromotionTypes::NORMAL
        ];
    }

    private function getImage()
    {
        if(Helper::getTradeNameHeader() == TradeName::MOVIETIME)
            return $this->image2_path ? $this->image2_path : asset('assets/img/no-product.png');
        else
            return $this->image_path ? $this->image_path : asset('assets/img/no-product.png');
    }
}
