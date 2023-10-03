<?php


namespace App\Http\Controllers\BackOffice\Movies;


use App\Http\Resources\BackOffice\Movies\MovieHeadquarterCollection;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Movies\Movie;

class MovieHeadquarterController
{
    private $headquarterRepository;

    public function __construct(
        HeadquarterRepositoryInterface $headquarterRepository
    ) {
        $this->headquarterRepository = $headquarterRepository;
    }

    public function index(Movie $movie)
    {
        $data = $this->headquarterRepository->headquartersAvailableOfTheMovie($movie);
        return MovieHeadquarterCollection::collection($data)->additional(['status' => 200]);
    }

}
