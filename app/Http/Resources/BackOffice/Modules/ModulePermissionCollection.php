<?php


namespace App\Http\Resources\BackOffice\Modules;


use App\Http\Resources\BackOffice\Permissions\PermissionCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ModulePermissionCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'permissions' => PermissionCollection::collection($this->permissions)
        ];
    }
}
