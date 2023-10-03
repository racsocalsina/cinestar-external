<?php


namespace App\Http\Resources\Awards;


use Illuminate\Http\Resources\Json\JsonResource;

class AwardInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code'   => $this->code,
            'name'   => $this->name,
            'points' => $this->points
        ];
    }
}
