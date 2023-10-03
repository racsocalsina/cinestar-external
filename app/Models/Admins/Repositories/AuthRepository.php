<?php


namespace App\Models\Admins\Repositories;


use App\Models\Admins\Admin;
use App\Models\Admins\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function getTokenResult(Admin $user)
    {
        return $user->createToken('BackOffice Access Token');
    }
}
