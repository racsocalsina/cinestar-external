<?php

namespace App\Http\Resources\Consumer\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'email'                 => $this['email'],
            'cellphone'             => $this['cellphone'],
            'name'                  => $this['name'],
            'lastname'              => $this['lastname'],
            'birthdate'             => $this['birthdate'],
            'document_type'         => $this['document_type'],
            'document_number'       => $this['document_number'],
            'image'                 => $this['image_path'],
            'ticket_points'         => isset($this['points_ticket_office']) ? $this['points_ticket_office'] : 0,
            'candy_points'          => isset($this['points_ticket_chocolate_shop']) ? $this['points_ticket_chocolate_shop'] : 0,
            'ticket_points_history' => isset($this['history_points_ticket_office']) ? $this['history_points_ticket_office'] : 0,
            'id_user'               => $this['id_user'],
            'username'              => $this['username'],
            'create_at'             => $this['create_at'],
            'expires_at'            => $this['expires_at'],
            'access_token'          => $this['access_token']
        ];
    }
}
