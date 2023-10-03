<?php


namespace App\Models\Modules\Repositories\Interfaces;


use App\Models\Admins\Admin;

interface ModuleRepositoryInterface
{
    public function getModulesWithPermissionsRelatedByUser(Admin $user);
    public function getModulesWithPermissionsRelated();
}
