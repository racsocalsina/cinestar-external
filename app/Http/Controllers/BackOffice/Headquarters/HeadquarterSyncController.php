<?php


namespace App\Http\Controllers\BackOffice\Headquarters;


use App\Http\Controllers\Controller;
use App\Jobs\SyncProcess;
use App\Models\Headquarters\Headquarter;
use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\Models\SyncLogs\Repositories\Interfaces\SyncLogRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponser;

class HeadquarterSyncController extends Controller
{
    use ApiResponser;

    private $syncLogRepository;
    private MovieRepositoryInterface $movieRepository;
    private MongoMovieRepositoryInterface $mongomovieRepository;

    public function __construct(
        SyncLogRepositoryInterface $syncLogRepository,
        MovieRepositoryInterface $movieRepository,
        MongoMovieRepositoryInterface $mongomovieRepository
    )
    {
        $this->syncLogRepository = $syncLogRepository;
        $this->movieRepository = $movieRepository;
        $this->mongomovieRepository = $mongomovieRepository;
    }

    public function sync(Headquarter $headquarter)
    {
        try {
            $this->syncLogRepository->create($headquarter);
            SyncProcess::dispatch($headquarter, $this->movieRepository, $this->mongomovieRepository);
            $response = $this->successResponse([]);
        } catch (\Exception $exception) {
            Log::error('Sync failed');
            Log::error($exception->getMessage());
            return $this->internalErrorResponse($exception);
        }
        return $response;
    }

}
