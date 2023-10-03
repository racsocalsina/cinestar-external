<?php


namespace App\Http\Resources\BackOffice\Shared;


use Illuminate\Http\Resources\Json\JsonResource;

class ListCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }
}
