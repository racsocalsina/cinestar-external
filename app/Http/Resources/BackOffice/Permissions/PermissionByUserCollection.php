<?php


namespace App\Http\Resources\BackOffice\Permissions;


use Illuminate\Http\Resources\Json\JsonResource;

class PermissionByUserCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'         => $this->name,
            'display_name' => $this->display_name,
            'allow'        => $this->allow
        ];
    }
}
