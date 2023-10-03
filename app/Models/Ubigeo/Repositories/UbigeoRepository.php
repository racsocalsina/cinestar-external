<?php


namespace App\Models\Ubigeo\Repositories;


use App\Models\Ubigeo\Repositories\Interfaces\UbigeoRepositoryInterface;
use App\Models\Ubigeo\UbDepartment;

class UbigeoRepository implements UbigeoRepositoryInterface
{
    private $model;

    public function __construct(UbDepartment $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('provinces.districts')
            ->orderBy('name')
            ->get();
    }
}
