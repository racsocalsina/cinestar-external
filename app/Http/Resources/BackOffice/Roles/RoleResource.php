<?php


namespace App\Http\Resources\BackOffice\Roles;


use App\Helpers\Helper;
use App\Http\Resources\BackOffice\Permissions\PermissionCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'display_name' => $this->display_name,
            'description'  => $this->description,
            'created_at'   => $this->created_at ? Helper::getDateTimeFormat($this->created_at) : null,
            'updated_at'   => $this->updated_at ? Helper::getDateTimeFormat($this->updated_at) : null,
            'permissions'  => PermissionCollection::collection($this->permissions)
        ];
    }

}
