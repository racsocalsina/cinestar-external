<?php


namespace App\Http\Resources\BackOffice\Roles;


use Illuminate\Http\Resources\Json\JsonResource;

class RoleCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'display_name' => $this->display_name,
            'description'  => $this->description,
        ];
    }

}
