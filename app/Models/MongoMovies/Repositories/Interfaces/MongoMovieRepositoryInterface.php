<?php

namespace App\Models\MongoMovies\Repositories\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface MongoMovieRepositoryInterface
{
    public function test(): JsonResponse;
    public function savemovies(array $movies, $trade_name);
    public function search(array $params);
}
