<?php


namespace App\Http\Resources\BackOffice\MovieGenders;


use App\Helpers\CastNameHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieGenderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'short'       => $this->short,
            'status'      => $this->status == 1,
            'status_name' => CastNameHelper::getEnabledName($this->status),
        ];
    }

}
