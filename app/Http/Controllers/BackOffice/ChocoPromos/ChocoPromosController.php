<?php


namespace App\Http\Controllers\BackOffice\ChocoPromos;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\ChocoPromotions\ChocoPromotionUpdateRequest;
use App\Http\Requests\Consumer\ChocoPromotion\ChocoPromotionRequest;
use App\Http\Resources\ChocoPromotions\ChocoPromotionResource;
use App\Models\ChocoPromotions\ChocoPromotion;
use App\Models\ChocoPromotions\Repositories\Interfaces\ChocoPromotionRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChocoPromosController extends Controller
{
    use ApiResponser;

    private ChocoPromotionRepositoryInterface $repository;

    public function __construct(ChocoPromotionRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->middleware('permission:read-promotion', ['only' => ['index', 'sync', 'syncProducts']]);
    }

    public function index(Request $request)
    {
        $data = $this->repository->searchBO($request->all());
        return ChocoPromotionResource::collection($data)->additional(['status' => 200]);
    }

    public function sync(ChocoPromotionRequest $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->repository->sync($request);
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }

    public function syncProducts(Request $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->repository->syncProducts($request->all());
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }

    public function update(ChocoPromotion $chocoPromotion, ChocoPromotionUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($chocoPromotion, $request);
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
