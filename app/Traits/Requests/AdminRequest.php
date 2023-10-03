<?php


namespace App\Traits\Requests;


use App\Enums\GlobalEnum;
use App\SearchableRules\AdminSearchableRule;
use App\Services\Searchable\Searchable;

trait AdminRequest
{
    protected function checkSuperAdminStatus($adminRepository, $user, $newStatus)
    {
        $searchableService = new Searchable();

        // check if is the only existing super-admin and the status will be disabled
        $query = $adminRepository->queryable();
        $searchableService->applyArray($query, new AdminSearchableRule(), [
            'role' => GlobalEnum::ROLE_NAME_SUPER_ADMIN,
            'status' => 1
        ]);
        $onlyOneSuperAdmin = $query->count();

        if ($onlyOneSuperAdmin == 1) {

            if($query->first()->id != $user->id){
              return true;
            }

            $statusFromUser = $user->status == 1;
            $statusHasChanged = $statusFromUser != boolval($newStatus);

            if (!$statusHasChanged) {
                return true;
            }

            return ['status' => 422, 'message' => __('app.admins.update_cannot_disable_unique_super_admin')];

        } else {
            return true;
        }
    }
}
