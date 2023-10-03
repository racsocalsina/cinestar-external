<?php

namespace App\Http\Resources\Consumer\Movies;

use App\Enums\GlobalEnum;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailResource extends JsonResource
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
            'name'                  => $this['name'],
            'image'                 => $this['image_path'] ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::MOVIES_FOLDER."/".$this['image_path'] : null,
            'url_trailer'           => $this['url_trailer'],
            'summary'               => $this['summary'],
            'duration_in_minutes'   => $this['duration_in_minutes'],
            'type_of_censorship'    => $this['type_of_censorship'],
            'premier_date'          => $this['premier_date'],
            'is_next_release'       => isset($this['premier_date']) && Carbon::createFromFormat('Y-m-d', $this['premier_date']) > now(),
            'gender_id'             => $this['id_movie_gender'],
            'gender_name'           => $this['name_movie_gender'],
            'country_id'            => $this['id_country'],
            'country_name'          => $this['name_country'],
        ];
    }
}
