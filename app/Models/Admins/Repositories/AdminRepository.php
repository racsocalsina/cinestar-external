<?php


namespace App\Models\Admins\Repositories;


use App\Enums\RoleEnum;
use App\Helpers\Helper;
use App\Models\Admins\Admin;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Permissions\Permission;
use App\SearchableRules\AdminSearchableRule;
use App\Services\Searchable\Searchable;
use Illuminate\Support\Facades\Hash;

class AdminRepository implements AdminRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Admin $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function search(array $params)
    {
        $query = $this->queryable();
        $this->searchableService->applyArray($query, new AdminSearchableRule(), $params);
        return $query->paginate(Helper::perPage($params));
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $model = $this->model->create($data);
        $model->attachRole($data['role']);

        if($data['role'] == RoleEnum::MARKETING){
            $model->headquarter_id = null;
            $model->syncPermissions($this->getPermissionsForMarketingRole());
        }

        return $model;
    }

    public function update(Admin $model, array $data)
    {
        if(array_key_exists('password', $data))
            if(!is_null($data['password']))
                $data['password'] = Hash::make($data['password']);

        if(array_key_exists('role', $data))
            if(!is_null($data['role']))
                $model->syncRoles([$data['role']]);

        if($data['role'] == RoleEnum::MARKETING){
            $model->headquarter_id = null;
            $model->syncPermissions($this->getPermissionsForMarketingRole());
        }

        $model->update($data);

        if($data['role'] == RoleEnum::MARKETING){
            $model->syncPermissions($this->getPermissionsForMarketingRole());
        }

        return $model;
    }

    private function getPermissionsForMarketingRole()
    {
        $permissions = Permission::all()->pluck('name')->toArray();
        unset($permissions[array_search('create-user', $permissions)]);
        unset($permissions[array_search('read-user', $permissions)]);
        unset($permissions[array_search('update-user', $permissions)]);
        unset($permissions[array_search('delete-user', $permissions)]);

        return $permissions;
    }

    public function delete(Admin $model)
    {
        $model->delete();
    }

    public function getByDocumentNumber(string $documentNumber, bool $dataRelated = false)
    {
        if($dataRelated)
            return $this->model->where('document_number', $documentNumber)->first();
        else
            return $this->model->with(['roles'])
                ->where('document_number', $documentNumber)->first();
    }
}
