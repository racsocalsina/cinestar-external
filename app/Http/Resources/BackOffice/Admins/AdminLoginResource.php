<?php


namespace App\Http\Resources\BackOffice\Admins;


use App\Helpers\Helper;
use App\Http\Resources\BackOffice\Modules\ModulePermissionByUserCollection;
use App\Models\Admins\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLoginResource extends JsonResource
{
    private $tokenResult;
    private $permissionPerModule;

    public function __construct(Admin $resource, $tokenResult, $permissionPerModule)
    {
        parent::__construct($resource);
        $this->tokenResult = $tokenResult;
        $this->permissionPerModule = $permissionPerModule;
    }

    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'lastname'           => $this->lastname,
            'email'              => $this->email,
            'document_type_id'   => $this->document_type_id,
            'document_type_name' => $this->document_type->name,
            'document_number'    => $this->document_number,
            'headquarter_id'     => $this->headquarter_id,
            'headquarter_name'   => $this->headquarter ? $this->headquarter->name : '',
            'entry_date'         => Helper::getDateFormat($this->entry_date),
            'role'               => count($this->roles) > 0 ? $this->roles->first()->name : null,
            'role_display_name'  => count($this->roles) > 0 ? $this->roles->first()->display_name : null,
            'token_type'         => 'bearer',
            'access_token'       => $this->tokenResult->accessToken,
            'expires_at'         => $this->tokenResult->token->expires_at,
            'modules'            => ModulePermissionByUserCollection::collection($this->permissionPerModule)
        ];
    }

}
