<?php

namespace App\Http\Resources\Consumer\Movies;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\GlobalEnum;

class MovieResource extends JsonResource
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
            'code'                  => $this['code'],
            'name'                  => $this['name'],
            'image'                 => $this['image_path'],
            'url_trailer'           => $this['url_trailer'],
            'summary'               => $this['summary'],
            'duration_in_minutes'   => $this['duration_in_minutes'],
            'type_of_censorship'    => $this['type_of_censorship'],
            'premier_date'          => $this['premier_date'],
            'gender_id'             => $this['movie_gender_id'],
            'gender_name'           => $this['movie_gender_name'],
            'country_id'            => $this['country_id'],
            'country_name'          => $this['country_name'],
            'headquarter_id'        => $this['headquarter_id'],
            'start_at'              => $this['start_at'],
            'city_id'               => $this['city_id'],
            'date_start'            => $this['date_start']
        ];
    }
}
