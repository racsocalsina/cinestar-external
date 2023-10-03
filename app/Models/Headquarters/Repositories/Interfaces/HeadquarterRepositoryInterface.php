<?php


namespace App\Models\Headquarters\Repositories\Interfaces;


use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface HeadquarterRepositoryInterface
{
    public function queryable();
    public function all();
    public function search(array $params, bool $pagination = true);
    public function get(int $id);
    public function create(array $params) : Headquarter;
    public function update(Headquarter $model, array $data);
    public function delete(Headquarter $model);
    public function listHeadquarters(Request $request);
    public function detailHeadquarter($headquarter);
    public function headquartersAvailableOfTheMovie(Movie $movie);
    public function test(): JsonResponse;
}
