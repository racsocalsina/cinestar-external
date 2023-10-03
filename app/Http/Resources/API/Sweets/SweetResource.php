<?php


namespace App\Http\Resources\API\Sweets;


use App\Enums\PromotionTypes;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class SweetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'image'      => $this->image ? Helper::getImageSweetPathByType($this->image) : asset('assets/img/no-product.png'),
            'price'      => floatval($this->price),
            'type_id'    => $this->type_id,
            'sweet_type' => $this->sweet_type,
            'choco_promotion_id' => null,
            'type' => PromotionTypes::NORMAL,
        ];
    }
}
