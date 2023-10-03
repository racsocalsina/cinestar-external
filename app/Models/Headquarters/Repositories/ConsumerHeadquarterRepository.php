<?php


namespace App\Models\Headquarters\Repositories;


use App\Models\Headquarters\Headquarter;
use App\Models\Headquarters\Repositories\Interfaces\ConsumerHeadquarterRepositoryInterface;

class ConsumerHeadquarterRepository implements ConsumerHeadquarterRepositoryInterface
{
    private $model;

    public function __construct(Headquarter $model)
    {
        $this->model = $model;
    }
    public function all()
    {
        return $this->model->newQuery()
            ->whereNotNull('local_url')
            ->whereRaw("ltrim(rtrim(local_url)) <> ''")
            ->get();
    }
}
