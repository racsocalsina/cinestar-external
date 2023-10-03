<?php


namespace App\Models\Cities\Repositories\Interfaces;


use App\Models\Cities\City;

interface CityRepositoryInterface
{
    public function queryable();
    public function listCities();
    public function search($params, $pagination = true);
    public function create(array $data);
    public function update(City $model, array $data);
    public function delete(City $model);
}
