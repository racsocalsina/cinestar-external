<?php


namespace App\Http\Resources\BackOffice\Admins;


use App\Helpers\CastNameHelper;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
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
            'status'             => $this->status == 1,
            'status_name'        => CastNameHelper::getEnabledName($this->status),
            'role_id'            => count($this->roles) > 0 ? $this->roles->first()->id : null,
            'role'               => count($this->roles) > 0 ? $this->roles->first()->name : null,
            'role_display_name'  => count($this->roles) > 0 ? $this->roles->first()->display_name : null,
            'created_at'         => $this->created_at ? Helper::getDateTimeFormat($this->created_at) : null,
            'updated_at'         => $this->updated_at ? Helper::getDateTimeFormat($this->updated_at) : null,
        ];
    }

}
