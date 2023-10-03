<?php

namespace App\Models\MongoMovies\Repositories;

use App\Models\MongoMovies\MongoMovie;
use App\Models\MongoMovies\Repositories\Interfaces\ConsumerMongoMoviesRepositoryInterface;

class ConsumerMongoMoviesRepository implements ConsumerMongoMoviesRepositoryInterface
{
    private $model;

    public function __construct(MongoMovie $model)
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
