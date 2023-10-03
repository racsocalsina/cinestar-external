<?php


namespace App\Http\Resources\BackOffice\Products;


use App\Helpers\CastNameHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductHeadquarterCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'stock'  => $this->stock,
            'active' => CastNameHelper::getEnabledName($this->active)
        ];
    }
}
