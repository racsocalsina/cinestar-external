<?php


namespace App\Models\MovieFormats\Repositories;

use App\Models\MovieFormats\MovieFormat;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;

class MovieFormatRepository implements MovieFormatRepositoryInterface
{
    private $model;

    public function __construct(MovieFormat $model)
    {
        $this->model = $model;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(MovieFormat $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete(MovieFormat $model)
    {
        $model->delete();
    }
}
