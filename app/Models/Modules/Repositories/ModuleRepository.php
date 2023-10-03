<?php


namespace App\Models\Modules\Repositories;


use App\Enums\GlobalEnum;
use App\Models\Admins\Admin;
use App\Models\Modules\Module;
use App\Models\Modules\Repositories\Interfaces\ModuleRepositoryInterface;

class ModuleRepository implements ModuleRepositoryInterface
{
    public function getModulesWithPermissionsRelatedByUser(Admin $user)
    {
        $permissionsByUser = $user->allPermissions();
        $permissionPerModule = Module::with(['permissions'])->get();

        $isSuperAdmin = $user->roles()->first()->name == GlobalEnum::ROLE_NAME_SUPER_ADMIN;

        $permissionPerModule->map(function ($item) use ($permissionsByUser, $isSuperAdmin) {

            $item->permissions->map(function ($permission) use ($permissionsByUser, $isSuperAdmin) {

                if($isSuperAdmin)
                {
                    $permission->allow = true;
                } else {

                    $exists = $permissionsByUser->filter(function ($permissionByUser) use ($permission) {
                            return $permissionByUser->name == $permission->name;
                        })->first() != null;

                    $permission->allow = $exists == true;
                }
            });
        });

        return $permissionPerModule;
    }

    public function getModulesWithPermissionsRelated()
    {
        return Module::with(['permissions'])
            ->orderBy('display_name')
            ->get();
    }

}
