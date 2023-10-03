<?php


namespace App\Models\Permissions\Repositories\Interfaces;



interface PermissionRepositoryInterface
{
    public function getAllByNames(array $names);
}
