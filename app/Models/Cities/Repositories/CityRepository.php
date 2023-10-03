<?php


namespace App\Models\Cities\Repositories;


use App\Helpers\Helper;
use App\Models\Cities\City;
use App\Models\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\SearchableRules\CitySearchableRule;
use App\Services\Searchable\Searchable;

class CityRepository implements CityRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(City $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }
    public function queryable()
    {
        return $this->model->query();
    }

    public function listCities()
    {
        return City::all();
    }

    public function search($params, $pagination = true)
    {
        $query = $this->queryable();
        $this->searchableService->applyArray($query, new CitySearchableRule(), $params);

        if($pagination)
            return $query->paginate(Helper::perPage($params));
        else
            return $query->get();
    }

    public function create(array $data)
    {
        $model = $this->model::create($data);;
        return $model;
    }

    public function update(City $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete(City $model)
    {
        $model->delete();
    }
}
