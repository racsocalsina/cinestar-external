<?php


namespace App\Models\UsersPartners\Repositories;


use App\Models\UsersPartners\Repositories\Interfaces\UserPartnerRepositoryInterface;
use App\Models\UsersPartners\UserPartner;

class UserPartnerRepository implements UserPartnerRepositoryInterface
{
    public function createFromExternal($customer)
    {
        $userPartner = UserPartner::where('soccod', $customer->socio_cod)->first();

        if($userPartner)
            return;

        UserPartner::create([
            'soccod' => $customer->socio_cod,
            'socnom' => $customer->name,
            'socno2' => $customer->lastname,
            'soctel' => $customer->cellphone,
            'socema' => $customer->email,
            'socdni' => $customer->document_number,
            'socnac' => $customer->birthdate,
            'soctdd' => $customer->document_type,
            'socsta' => $customer->status,
            'socing' => $customer->registration_date,
        ]);
    }
}