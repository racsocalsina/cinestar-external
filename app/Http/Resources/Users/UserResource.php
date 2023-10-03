<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Profiles\ProfileResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id_user'    => $this->id,
            'email'      => $this->email,
            'type'       => $this->type,
            'fk_profile' => $this->fk_profile,
            'photo'      => $this->photo,
            'names'      => $this->name,
            'last_names' => $this->lastname
        ];
    }
}
