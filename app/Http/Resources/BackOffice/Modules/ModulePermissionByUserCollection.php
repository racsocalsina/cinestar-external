<?php


namespace App\Http\Resources\BackOffice\Modules;


use App\Http\Resources\BackOffice\Permissions\PermissionByUserCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ModulePermissionByUserCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'permissions' => PermissionByUserCollection::collection($this->permissions)
        ];
    }
}
