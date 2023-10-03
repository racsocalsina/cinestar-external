<?php

namespace App\Http\Controllers\BackOffice\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Movies\MovieStoreRequest;
use App\Http\Requests\BackOffice\Movies\MovieUpdateRequest;
use App\Http\Resources\BackOffice\Movies\MovieResource;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Jobs\mongo\SyncMovies;
use App\Models\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Models\MovieGenders\Repositories\Interfaces\MovieGenderRepositoryInterface;
use App\Models\Movies\Movie;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Controllers\ChangeStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Consumer\Movies\MovieListRequest;
use App\Http\Controllers\API\MoviesController;
use MailPoet\Config\Env;
use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;

class MovieController extends Controller
{
    use ApiResponser, ChangeStatus;

    private $movieRepository;
    private $countryRepository;
    private $movieGenderRepository;
    private $repository;
    private $movieController;
    protected $mongomovieRepository;

    public function __construct(
        Movie $repository,
        MovieRepositoryInterface $movieRepository,
        MovieGenderRepositoryInterface $movieGenderRepository,
        CountryRepositoryInterface $countryRepository,
        MoviesController $movieController,
        MongoMovieRepositoryInterface $mongomovieRepository
    ) {
        $this->repository = $repository;
        $this->movieRepository = $movieRepository;
        $this->movieGenderRepository = $movieGenderRepository;
        $this->countryRepository = $countryRepository;
        $this->movieController = $movieController;
        $this->mongomovieRepository = $mongomovieRepository;

        $this->middleware('permission:read-movie', ['only' => ['index']]);
        $this->middleware('permission:update-movie', ['only' => ['update']]);
    }

    public function index(Request $request)
    {
        $data = $this->movieRepository->search($request->all());
        return MovieResource::collection($data)->additional(['status' => 200]);
    }

    public function sync(MovieStoreRequest $request, MovieListRequest $request2)
    {
        try {
            $body = $request->all();
            DB::beginTransaction();
            $res = $this->movieRepository->sync($body);
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();

        Log::info("Sync ");
        // Proceso Sync Automatico
        $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();
        Log::info("Sync 2 ");
        Log::info($syncHeadquarter->trade_name);

        SyncMovies::dispatch($this->movieRepository, $this->mongomovieRepository, $syncHeadquarter->trade_name, "Automatic", $body['data']['code'])
            ->onQueue('SYNC_MONGO');

        return $res;
    }

    public function parameters()
    {
        $countries = $this->countryRepository->all();
        $movieGenders = $this->movieGenderRepository->all();

        $data = [
            'countries'        => ListCollection::collection($countries),
            'movie_genders' => ListCollection::collection($movieGenders),
        ];
        return $this->success($data);
    }

    public function update(Movie $movie, MovieUpdateRequest $request){
        try {
            DB::beginTransaction();
            $movieUpdate =  new MovieResource($this->movieRepository->update($movie, $request));
            $response = $this->successResponse($movieUpdate);
        } catch (Exception $exception) {
            $message = 'Error al actualizar la película. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function toggleStatus(Movie $movie){
        $movie->update(['status' => !$movie->status]);
        $response = $this->successResponse($movie);
        SyncMovies::dispatch($this->movieRepository, $this->mongomovieRepository, null, "Automatic", $movie->code)
            ->onQueue('SYNC_MONGO');
        return $response;
    }
}


