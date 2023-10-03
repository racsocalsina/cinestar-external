<?php


namespace App\Http\Resources\API\Partners;


use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image_path,
            'start_date' => $this->start_date->format('d/m/Y'),
            'end_date' => $this->end_date->format('d/m/Y'),
            'award_id' => $this->award ? $this->award->id : null,
            'type' => $this->resource instanceof TicketPromotion ? 'ticket' : 'choco'
        ];
    }
}
