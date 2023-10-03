<?php


namespace App\Http\Resources\BackOffice\Movies;


use Illuminate\Http\Resources\Json\JsonResource;

class MovieListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name
        ];
    }
}
