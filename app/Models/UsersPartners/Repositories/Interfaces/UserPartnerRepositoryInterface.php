<?php


namespace App\Models\UsersPartners\Repositories\Interfaces;


interface UserPartnerRepositoryInterface
{
    public function createFromExternal($customer);
}