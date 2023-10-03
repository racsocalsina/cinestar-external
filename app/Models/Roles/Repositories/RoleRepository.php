<?php


namespace App\Models\Roles\Repositories;


use App\Helpers\Helper;
use App\Models\Roles\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Roles\Role;
use App\SearchableRules\RoleSearchableRule;
use App\Services\Searchable\Searchable;

class RoleRepository implements RoleRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Role $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function search($params, $pagination = true)
    {
        $query = $this->queryable()
            ->with(['permissions']);

        $this->searchableService->applyArray($query, new RoleSearchableRule(), $params);

        if($pagination)
            return $query->paginate(Helper::perPage($params));
        else
            return $query->get();
    }

    public function get(int $id)
    {
        return $this->model::find($id);
    }

    public function create(array $data)
    {
        $permissions = $data['permissions'];
        unset($data['permissions']);

        $role = $this->model::create($data);;
        $role->attachPermissions($permissions);
        return $role;
    }

    public function update(Role $model, array $data)
    {
        $permissions = $data['permissions'];
        unset($data['permissions']);

        $model->update($data);
        $model->syncPermissions($permissions);
        return $model;
    }

    public function delete(Role $model)
    {
        $model->delete();
    }

}
