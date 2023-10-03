<?php


namespace App\Http\Controllers\BackOffice\TicketPromotions;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\TicketPromotions\TicketPromotionUpdateRequest;
use App\Http\Requests\Consumer\TicketPromotion\TicketPromotionRequest;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Http\Resources\TicketPromotions\TicketPromotionResource;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketPromotionController extends Controller
{
    use ApiResponser;

    private TicketPromotionRepositoryInterface $repository;
    private HeadquarterRepositoryInterface $headquarterRepository;

    public function __construct(TicketPromotionRepositoryInterface $repository, HeadquarterRepositoryInterface $headquarterRepository)
    {
        $this->repository = $repository;

        $this->middleware('permission:read-promotion', ['only' => ['index']]);
        $this->middleware('permission:create-promotion', ['only' => ['store']]);
        $this->headquarterRepository = $headquarterRepository;
    }

    public function parameters()
    {
        $data = [
            'headquarters' => ListCollection::collection($this->headquarterRepository->all()),
        ];
        return $this->success($data);
    }

    public function index(Request $request)
    {
        $data = $this->repository->searchBO($request->all());
        return TicketPromotionResource::collection($data)->additional(['status' => 200]);
    }

    public function sync(TicketPromotionRequest $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->repository->sync($request);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }

    public function update(TicketPromotion $ticketPromotion, TicketPromotionUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($ticketPromotion, $request);
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
