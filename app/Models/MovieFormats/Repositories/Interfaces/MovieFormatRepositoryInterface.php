<?php


namespace App\Models\MovieFormats\Repositories\Interfaces;


use App\Models\MovieFormats\MovieFormat;
use App\Models\Roles\Role;

interface MovieFormatRepositoryInterface
{
    public function all();
    public function queryable();
    public function create(array $data);
    public function update(MovieFormat $model, array $data);
    public function delete(MovieFormat $model);
}
