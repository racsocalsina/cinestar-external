<?php


namespace App\Models\Countries\Repositories;


use App\Models\Countries\Country;
use App\Models\Countries\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    private $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function create($data)
    {
        $model = $this->model::create($data);
        return $model;
    }

    public function update(Country $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function destroy(Country $model)
    {
        $model->delete();
    }
}
