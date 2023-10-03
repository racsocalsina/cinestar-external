<?php


namespace App\Http\Controllers\BackOffice\TicketAwards;


use App\Http\Requests\BackOffice\TicketAwards\TicketAwardUpdateRequest;
use App\Http\Resources\Awards\TicketAwardResource;
use App\Models\TicketAwards\Repositories\Interfaces\TicketAwardRepositoryInterface;
use App\Models\TicketAwards\TicketAward;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketAwardController
{
    use ApiResponser;

    private TicketAwardRepositoryInterface $ticketAwardRepository;

    public function __construct(TicketAwardRepositoryInterface $ticketAwardRepository)
    {
        $this->ticketAwardRepository = $ticketAwardRepository;
    }

    public function index(Request $request)
    {
        $data = $this->ticketAwardRepository->searchBO($request->all());
        return TicketAwardResource::collection($data)->additional(['status' => 200]);
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->ticketAwardRepository->sync($request->all());
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }

    public function update(TicketAward $ticketAward, TicketAwardUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->ticketAwardRepository->update($ticketAward, $request);
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
