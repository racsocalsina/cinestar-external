<?php


namespace App\Models\MovieGenders\Repositories\Interfaces;


use App\Models\MovieGenders\MovieGender;

interface MovieGenderRepositoryInterface
{
    public function queryable();
    public function search($params, $pagination = true);
    public function listGenders();
    public function get(int $id);
    public function create(array $data);
    public function update(MovieGender $model, array $data);
    public function delete(MovieGender $model);
    public function all();
}
