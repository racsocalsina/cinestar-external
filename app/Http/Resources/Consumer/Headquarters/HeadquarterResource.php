<?php

namespace App\Http\Resources\Consumer\Headquarters;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'       => $this->name,
            'local_url'  => Helper::addSlashToUrl($this->local_url),
            'trade_name' => $this->trade_name
        ];
    }
}
