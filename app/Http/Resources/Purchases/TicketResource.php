<?php


namespace App\Http\Resources\Purchases;


use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'seat_name' => $this->seat_name,
            'remote_funtar' => $this->movie_time_tariff->movie_tariff->remote_funtar,
            'purchase_id' => $this->purchase_id
        ];
    }
}
