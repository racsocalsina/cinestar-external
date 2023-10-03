<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Awards\AwardInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'email'                   => $this['email'],
            'cellphone'               => $this['cellphone'],
            'name'                    => $this['name'],
            'lastname'                => $this['lastname'],
            'birthdate'               => $this['birthdate'],
            'image'                   => $this['image_path'],
            'document_type'           => $this['document_type'],
            'document_number'         => $this['document_number'],
            'department'              => $this['department'],
            'ticket_promotional_data' => [
                'total_points'     => isset($this['ticket_promotional_data']) ? $this['ticket_promotional_data']['points'] : 0,
                'movements'  => isset($this['ticket_promotional_data']) ? $this['ticket_promotional_data']['movements'] : [],
                'promotions' => isset($this['ticket_promotional_data']) ? AwardInfoResource::collection($this['ticket_promotional_data']['awards']) : [],
            ],
            'choco_promotional_data' => [
                'total_points'     => isset($this['choco_promotional_data']) ? $this['choco_promotional_data']['points'] : 0,
                'movements'  => isset($this['choco_promotional_data']) ? $this['choco_promotional_data']['movements'] : [],
                'promotions' => isset($this['choco_promotional_data']) ? AwardInfoResource::collection($this['choco_promotional_data']['awards']) : [],
            ]
        ];
    }
}
