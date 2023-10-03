<?php


namespace App\Http\Resources\Consumer\MovieTimes;


use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterMovieFormatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $headquarter = $this['movie_times'][0]['headquarter'];
        return [
            'id' => $headquarter['id'],
            'name' => $headquarter['name'],
            'address' => $headquarter['address'],
            'latitude' => $headquarter['latitude'],
            'longitude' => $headquarter['longitude'],
            'movie_times' => MovieTimeResource::collection($this['movie_times'])
        ];
    }
}
