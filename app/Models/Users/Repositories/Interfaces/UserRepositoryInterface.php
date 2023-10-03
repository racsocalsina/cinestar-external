<?php

namespace App\Models\Users\Repositories\Interfaces;

use App\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function createCustomer($data);

    public function changePassword($data);

    public function getDataProfile($user);

    public function editProfile($user, Request $request);

    public function editImageProfile($user, Request $request);

    public function userIsRecurringBuyer(User $user);

    public function getRegisterUserDays($user);

    public function getAntifraudData();
}
