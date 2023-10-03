<?php


namespace App\Http\Resources\BackOffice\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'lastname'           => $this->lastname,
            'email'              => $this->email,
            'cellphone'          => $this->cellphone,
            'trade_name'         => $this->trade_name,
            'birthdate'          => date("d-m-Y", strtotime($this->birthdate)),
            $this->mergeWhen($this->amount, [
                'amount'=>$this->amount
            ]),
            'department'         => $this->department
        ];
    }

}
