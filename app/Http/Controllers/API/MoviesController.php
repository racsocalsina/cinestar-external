<?php


namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\MongoMovies\MongoMovie;
use App\Models\MongoMovies\MongoPelis;
use Illuminate\Http\Request;
use App\Models\Movies\Movie;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ApiController;
use App\Services\Searchable\Searchable;
use App\Http\Resources\Consumer\Movies\MovieResource;
use App\Http\Requests\Consumer\Movies\MovieListRequest;
use App\Http\Resources\Consumer\Movies\MovieDetailResource;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\SearchableRules\SearchableJoinRules\MovieSearchableJoinRule;
use App\Http\Resources\Consumer\Movies\MovieAndMovieTimeResource;
use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;

class MoviesController extends ApiController
{
    private $movieRepository;
    private $searchableService;
    private $mongoMoviesRepository;

    public function __construct(MovieRepositoryInterface $movieRepository,
    Searchable $searchableService,
    MongoMovieRepositoryInterface $mongoMoviesRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->searchableService = $searchableService;
        $this->mongoMoviesRepository = $mongoMoviesRepository;
    }

    public function listByHeadquarter(Request $request, int $id)
    {
        $res = $this->movieRepository->listMoviesByHeadquarter($id, $request);
        return $this->successResponse($res);
    }

    public function newlistMovies(MovieListRequest $request)
    {
        $parameters = request()->all();

        $movies = $this->mongoMoviesRepository->search($parameters);

        return $this->successResponse($movies);
    }

    public function listMovies(MovieListRequest $request)
    {

        $parameters = request()->all();
        $cacheName = "movies_next_";

        foreach ($parameters as $key => $param) {
            $cacheName .= $key . ":" . $param . "_";
        }

        $movies = Cache::remember($cacheName, 600, function () use ($request) {
                    $query = $this->movieRepository->listMovies(Helper::getTradeNameHeader(), $request->date);
            $this->searchableService->applyArray($query, new MovieSearchableJoinRule(), $request->all());
            $query->orderByRaw('(select count(*) from `movie_times` as x where x.movie_id = movies.id and x.is_presale > 0) desc');

            if($request->has('limit')){
                $listMovies = MovieResource::collection(
                    $query->limit($request->limit)
                        ->groupBy('movies.id')
                        ->get([
                            'movies.id',
                            'movies.code',
                            'movies.is_3d',
                            'movies.name',
                            'movies.image_path',
                            'movies.url_trailer',
                            'movies.summary',
                            'movies.duration_in_minutes',
                            'movies.type_of_censorship',
                            'movies.premier_date',
                            'movie_genders.id AS movie_gender_id',
                            'movie_genders.name AS movie_gender_name',
                            'countries.id AS country_id',
                            'countries.name AS country_name'
                        ])
                );
            }else{
                $listMovies = MovieResource::collection(
                    $query->groupBy('movies.id')
                        ->get([
                            'movies.id',
                            'movies.code',
                            'movies.is_3d',
                            'movies.name',
                            'movies.image_path',
                            'movies.url_trailer',
                            'movies.summary',
                            'movies.duration_in_minutes',
                            'movies.type_of_censorship',
                            'movies.premier_date',
                            'movie_genders.id AS movie_gender_id',
                            'movie_genders.name AS movie_gender_name',
                            'countries.id AS country_id',
                            'countries.name AS country_name'
                        ])
                );
            }

            return $listMovies;
        });

        return $this->successResponse($movies);
    }

    public function detailMovie($movie)
    {
        $movie = Movie::findOrFail($movie);
        $detailMovie = new MovieDetailResource($this->movieRepository->detailMovie($movie));
        return $this->successResponse($detailMovie);
    }

    public function listMoviesAndMovieTimes(MovieListRequest $request)
    {
        $parameters = request()->all();
        $cacheName = "movies_next_";

        foreach ($parameters as $key => $param) {
            $cacheName .= $key . ":" . $param . "_";
        }

        $cacheKey = 'custom_cache_key_' . $cacheName; // Cambia 'custom_cache_key_' a la clave deseada

        $movies = Cache::remember($cacheKey, 300, function () use ($request) {
            $query = $this->movieRepository->listMovies(Helper::getTradeNameHeader(), $request->date);
            //$this->searchableService->applyArray($query, new MovieSearchableJoinRule(), $request->all());

            if ($request->has('limit')) {
                $query->limit($request->limit);
            }

            $listMovies = MovieAndMovieTimeResource::collection(
                $query->groupBy('movies.id')
                    ->distinct()
                    ->get([
                        'movies.id',
                        'movies.code',
                        'movies.is_3d',
                        'movies.name',
                        'movies.image_path',
                        'movies.url_trailer',
                        'movies.summary',
                        'movies.duration_in_minutes',
                        'movies.type_of_censorship',
                        'movies.premier_date',
                        'movie_genders.id AS movie_gender_id',
                        'movie_genders.name AS movie_gender_name',
                        'countries.id AS country_id',
                        'countries.name AS country_name'
                    ])
                    ->load(['movie_times' => function ($query) use ($request) {
                        $query->where('headquarter_id', $request->headquarter_id)
                        ->filterByStartDate($request->start_at ?? now()->format('Y-m-d'));
                    }])
            );

            $listMovies = $listMovies->filter(function ($movie) {
                return $movie->movie_times->isNotEmpty();
            })->values();

            return $listMovies;
        });
        return $this->successResponse($movies);
    }
}
