<?php


namespace App\Http\Controllers\BackOffice\ChocoAwards;


use App\Http\Requests\BackOffice\ChocoAwards\ChocoAwardUpdateRequest;
use App\Http\Resources\Awards\ChocoAwardResource;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\ChocoAwards\Repositories\Interfaces\ChocoAwardRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChocoAwardController
{
    use ApiResponser;

    private ChocoAwardRepositoryInterface $repository;

    public function __construct(ChocoAwardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = $this->repository->searchBO($request->all());
        return ChocoAwardResource::collection($data)->additional(['status' => 200]);
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->repository->sync($request->all());
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }

    public function update(ChocoAward $chocoAward, ChocoAwardUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($chocoAward, $request);
            $response = $this->success();
        } catch (\Exception $exception) {
            $message = 'Error al actualizar. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }
}
