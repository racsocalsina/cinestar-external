<?php


namespace App\Http\Resources\BackOffice\Movies;


use App\Helpers\CastNameHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieHeadquarterCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'address'            => $this->address,
            'latitude'           => $this->latitude,
            'longitude'          => $this->longitude,
            'city_name'            => $this->city->name,
            'status'             => $this->status == 1,
            'status_name'        => CastNameHelper::getEnabledName($this->status),
        ];
    }
}
