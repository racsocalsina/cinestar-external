<?php


namespace App\Http\Resources\API\Partners;


use App\Models\TicketAwards\TicketAward;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image_path,
            'type'        => $this->resource instanceof TicketAward ? 'ticket' : 'choco'
        ];
    }
}
