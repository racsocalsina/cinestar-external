<?php


namespace App\Http\Resources\BackOffice\Products;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'image'     => $this->image_path,
            'image2'     => $this->image2_path,
            'type_name' => $this->type ? $this->type->name : null
        ];
    }
}
