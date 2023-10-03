<?php


namespace App\Models\MovieGenders\Repositories;

use App\Helpers\Helper;
use App\Models\MovieGenders\MovieGender;
use App\Models\MovieGenders\Repositories\Interfaces\MovieGenderRepositoryInterface;
use App\SearchableRules\MovieGenderSearchableRule;
use App\Services\Searchable\Searchable;

class MovieGenderRepository implements MovieGenderRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(MovieGender $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function listGenders()
    {
        return MovieGender::all();
    }

    public function search($params, $pagination = true)
    {
        $query = $this->queryable();
        $this->searchableService->applyArray($query, new MovieGenderSearchableRule(), $params);

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
        $model = $this->model::create($data);;
        return $model;
    }

    public function update(MovieGender $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete(MovieGender $model)
    {
        $model->delete();
    }

    public function all()
    {
        return $this->model::all();
    }
}
