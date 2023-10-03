<?php


namespace App\Http\Resources\BackOffice\DocumentTypes;


use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
        ];
    }

}
