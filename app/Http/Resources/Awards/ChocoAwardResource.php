<?php


namespace App\Http\Resources\Awards;


use Illuminate\Http\Resources\Json\JsonResource;

class ChocoAwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'code'         => $this->code,
            'name'         => $this->name,
            'points'       => $this->points,
            'product'      => $this->product,
            'restrictions' => $this->restrictions,
            'unit'         => $this->unit,
            'description'  => $this->description,
            'image'        => $this->image_path,
        ];
    }
}
