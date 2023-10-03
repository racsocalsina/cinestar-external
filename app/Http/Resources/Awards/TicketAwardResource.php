<?php


namespace App\Http\Resources\Awards;


use Illuminate\Http\Resources\Json\JsonResource;

class TicketAwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'points'         => $this->points,
            'product'        => $this->product,
            'restrictions'   => $this->restrictions,
            'unit'           => $this->unit,
            'promotion_code' => $this->promotion ? $this->promotion->code : null,
            'promotion_name' => $this->promotion ? $this->promotion->name : null,
            'description'    => $this->description,
            'image'          => $this->image_path,
        ];
    }
}
