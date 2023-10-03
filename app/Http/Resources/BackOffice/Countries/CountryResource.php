<?php


namespace App\Http\Resources\BackOffice\Countries;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name
        ];
    }

}
