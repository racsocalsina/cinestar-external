<?php


namespace App\Http\Resources\BackOffice\Movies;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'code'                => $this->code,
            'name'                => $this->name,
            'image'               => $this->image_path,
            'url_trailer'         => $this->url_trailer,
            'summary'             => $this->summary,
            'duration_in_minutes' => $this->duration_in_minutes,
            'type_of_censorship'  => $this->type_of_censorship,
            'premier_date'        => $this->premier_date ? Carbon::createFromFormat('Y-m-d', $this['premier_date'])->format('d/m/y') : null,
            'gender_id'           => $this->gender ? $this->gender->id : null,
            'gender_name'         => $this->gender ? $this->gender->name : null,
            'country_id'          => $this->country ? $this->country->id : null,
            'country_name'        => $this->country ? $this->country->name : null,
            'status'              => $this->status == 1
        ];
    }
}
