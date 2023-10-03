<?php


namespace App\Models\Permissions\Repositories;


use App\Models\Permissions\Permission;
use App\Models\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAllByNames(array $names)
    {
        return Permission::whereIn('name', $names)->get();
    }
}
