<?php


namespace App\Http\Controllers\BackOffice\MovieTimes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consumer\MovieTime\SyncMovieTimeRequest;
use App\Http\Requests\Consumer\MovieTime\UpdateMovieTimeRequest;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterMovieTimeResource;
use App\Models\MovieTimes\MovieTime;
use App\Models\MovieTimes\Repositories\Interfaces\MovieTimeRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Controllers\ChangeStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieTimeController extends Controller
{
    use ApiResponser;

    private $movieTimeRepository;
    private $repository;

    public function __construct(
        MovieTime $repository,
        MovieTimeRepositoryInterface $movieTimeRepository
    ) {
        $this->movieTimeRepository = $movieTimeRepository;
        $this->repository = $repository;
    }

    public function updateGraph(UpdateMovieTimeRequest $request){
        try {
            DB::beginTransaction();
            $this->movieTimeRepository->updateGraph($request);
            $response = $this->success();
        } catch (Exception $exception) {
            $message = 'Error al actualizar el gráfico. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function update(Request $request, MovieTime $movieTime){
        try {
            DB::beginTransaction();
            $movieTime = $this->movieTimeRepository->update($movieTime, $request->all());
            $response = $this->success(new HeadquarterMovieTimeResource($movieTime));
        } catch (Exception $exception) {
            $message = 'Error al actualizar movie times. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function sync(SyncMovieTimeRequest $request){
        try {
            DB::beginTransaction();
            $this->movieTimeRepository->sync($request->all());
            $response = $this->success();
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }

    public function toggleStatus($id, Request $req)
    {
        $model = $this->repository->findOrFail($id);
        $model->update([
            'active' => !$model->active
        ]);
       return $this->success(new HeadquarterMovieTimeResource($model));
    }

}
