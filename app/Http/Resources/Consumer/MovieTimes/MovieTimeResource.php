<?php

namespace App\Http\Resources\Consumer\MovieTimes;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this['id'],
            'room_id'               => $this['room_id'],
            'movie_id'              => $this['movie_id'],
            'headquarter_id'        => $this['headquarter_id'],
            'remote_funkey'         => $this['remote_funkey'],
            'fun_nro'               => $this['fun_nro'],
            'date_start'            => $this['date_start'],
            'time_start'            => $this['time_start']
        ];
    }
}
