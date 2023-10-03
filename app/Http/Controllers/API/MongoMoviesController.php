<?php

namespace App\Http\Controllers\Api;

use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use App\Http\Controllers\ApiController;
use App\Models\MongoMovies\MongoMovie;
use Illuminate\Http\Request;
use App\Models\MongoMovies\Repositories\MongoMovieRepository;
use App\Services\Searchable\Searchable;
use App\Models\Movies\Movie;
use App\Http\Resources\Consumer\Movies\MovieDetailResource;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\Http\Resources\BackOffice\MongoMovies\MongoMovieResource;
use Illuminate\Support\Facades\Log;

class MongoMoviesController extends ApiController
{
    private $mongomovieRepository;
    private $searchableService;
    private $movieRepository;

    public function __construct(MongoMovieRepositoryInterface $mongomovieRepository,
    Searchable $searchableService,
    MovieRepositoryInterface $movieRepository)
    {
        $this->mongomovieRepository = $mongomovieRepository;
        $this->searchableService = $searchableService;
        $this->movieRepository = $movieRepository;
    }

    public function index(Request $request)
    {
        $res = $this->mongomovieRepository->search($request->all());
        return $this->successResponse($res);
    }
}
