<?php


namespace App\Models\Movies\Repositories\Interfaces;


use Illuminate\Http\Request;

interface MovieRepositoryInterface
{
    public function queryable();
    public function search(array $params);
    public function sync($body, $syncHeadquarter = null);
    public function listMoviesByHeadquarter(int $headquarterId, Request $request);
    public function listMovies($trade_id, $date = null);
    public function update($movie, $request);
    public function detailMovie($movie);
    public function all($params);
    public function listMoviesSyncMongo($trade_name = null, $code = null);
}
