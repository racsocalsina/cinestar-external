<?php


namespace App\Models\Countries\Repositories\Interfaces;


use App\Models\Countries\Country;

interface CountryRepositoryInterface
{
    public function all();
    public function create($data);
    public function update(Country $model, array $data);
    public function destroy(Country $model);
}
