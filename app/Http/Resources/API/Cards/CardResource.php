<?php


namespace App\Http\Resources\API\Cards;


use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token'          => $this->token,
            'payment_method' => $this->payment_method,
            'full_name'      => $this->full_name,
            'masked_number'  => $this->masked_number,
        ];
    }
}
