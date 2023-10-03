<?php


namespace App\Http\Resources\BackOffice\MovieFormats;


use App\Helpers\CastNameHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieFormatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'short'       => $this->short,
            'description' => $this->description,
            'status'      => $this->status == 1,
            'status_name' => CastNameHelper::getEnabledName($this->status),
        ];
    }

}
