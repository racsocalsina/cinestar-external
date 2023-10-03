<?php


namespace App\Models\Roles\Repositories\Interfaces;


use App\Models\Roles\Role;

interface RoleRepositoryInterface
{
    public function all();
    public function queryable();
    public function search($params, $pagination = true);
    public function get(int $id);
    public function create(array $data);
    public function update(Role $model, array $data);
    public function delete(Role $model);
}
