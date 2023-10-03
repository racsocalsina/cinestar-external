<?php


namespace App\Http\Resources\BackOffice\Headquarters;


use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterMovieTimeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'fun_nro'       => $this->fun_nro,
            'date_start'    => $this->date_start,
            'time_start'    => $this->time_start,
            'active'            => $this->active,
            'movie'         => [
                'name' => $this->movie ? $this->movie->name : null,
            ],
            'room'          => [
                'room_number' => $this->room ? $this->room->room_number : null,
                'name'        => $this->room ? $this->room->name : null,
            ]
        ];
    }
}
