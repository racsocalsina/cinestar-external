<?php

namespace App\Models\MongoMovies\Repositories\Interfaces;

use App\Models\MongoMovies\MongoMovie;
use Illuminate\Http\Request;

interface ConsumerMongoMoviesRepositoryInterface
{
    public function all();
}
