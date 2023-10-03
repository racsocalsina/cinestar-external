<?php


namespace App\Models\Admins\Repositories\Interfaces;


use App\Models\Admins\Admin;

interface AuthRepositoryInterface
{
    public function getTokenResult(Admin $user);
}
