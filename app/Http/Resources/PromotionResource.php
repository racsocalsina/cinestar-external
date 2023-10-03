<?php

namespace App\Http\Resources;

use App\Enums\SalesType;
use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image ? url($this->image) : null,
            'type' => $this->resource instanceof TicketPromotion ? SalesType::TICKET : SalesType::SWEET
        ];
    }
}
