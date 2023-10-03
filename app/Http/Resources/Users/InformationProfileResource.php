<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\FileStorageS3;

class InformationProfileResource extends JsonResource
{
    use FileStorageS3;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $photo = $this->photo ? $this->getFileS3TempFullPath($this->photo) : '';
        return [
            'id_user' => $this->id,
            'email' => $this->email,
            'type' => $this->type,
            'fk_profile' => $this->fk_profile,
            'photo' => $photo,
            'name'  => $this->name,
            'lastname' => $this->lastname
        ];
    }
}
