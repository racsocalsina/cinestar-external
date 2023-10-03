<?php


namespace App\Http\Resources\BackOffice\Headquarters;


use App\Helpers\CastNameHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name
        ];
    }
}
