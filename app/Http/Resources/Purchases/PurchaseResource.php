<?php


namespace App\Http\Resources\Purchases;


use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'purchase'       => [
                'id'             => $this['purchase']['id'],
                'movie_time_id'  => $this['purchase']['movie_time_id'],
                'movie_id'       => $this['purchase']['movie_id'],
                'headquarter_id' => $this['purchase']['headquarter_id'],
                'amount'         => $this['purchase']['amount'],
                'number_tickets' => $this['purchase']['number_tickets'],
            ],
            'graph'          => isset($this['graph']) ? $this['graph'] : null,
            'original_graph' => isset($this['original_graph']) ? $this['original_graph'] : null,
            'business_name'  => isset($this['business_name']) ? $this['business_name'] : null,
            'antifraud_data' => isset($this['antifraud_data']) ? $this['antifraud_data'] : null,
        ];
    }
}
