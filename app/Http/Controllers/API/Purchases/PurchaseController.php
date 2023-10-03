<?php


namespace App\Http\Controllers\API\Purchases;

use App\Helpers\FunctionHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Purchase\PurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Http\Requests\Purchase\UpdateSeatRequest;
use App\Http\Resources\Purchases\PurchasePaymentDataResource;
use App\Http\Resources\Purchases\PurchaseResource;
use App\Jobs\SendErrorEmail;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends ApiController
{
    private $purchaseRepository;
    private $userRepository;

    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->userRepository = $userRepository;
    }

    public function store(PurchaseRequest $request) {
        try {
            DB::beginTransaction();
            $purchase = $this->purchaseRepository->create($request);
            $purchase['antifraud_data'] = $this->userRepository->getAntifraudData();

            $data = new PurchaseResource($purchase);
            $response = $this->successResponse($data);
        } catch (Exception $exception) {
            DB::rollBack();
            $message = 'Error al registrar la compra. Inténtelo nuevamente.';
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
        }
        DB::commit();
        return $response;
    }

    public function update(int $id, UpdatePurchaseRequest $request) {
        try {
            DB::beginTransaction();
            $purchase = $this->purchaseRepository->update($id, $request);
            $data = new PurchaseResource($purchase);
            $response = $this->successResponse($data);
        } catch (Exception $exception) {
            $message = 'Error al actualizar la compra. Inténtelo nuevamente.';
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function getPlannerGraph(int $id) {
        try {
            $purchase = $this->purchaseRepository->getGraphByUser($id);
            $response = $this->successResponse($purchase);
        } catch (Exception $exception) {
            $message = 'Error al obtener el gráfico. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
        }
        return $response;
    }

    public function updateSeat(int $id, UpdateSeatRequest $request) {
        try {
            DB::beginTransaction();
            $purchase = $this->purchaseRepository->updateSeats($id, $request);
            $response = $this->successResponse($purchase);
        } catch (Exception $exception) {
            $message = 'Error al actualizar butacas. Inténtelo nuevamente.';

            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function destroy() {
        try {
            DB::beginTransaction();
            $response = $this->successResponse([ 'message' => 'Compra cancelada']);
        } catch (Exception $exception) {
            $message = 'Error al cancelar la compra. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function index()
    {
        $data = $this->purchaseRepository->getAllConfirmedPurchasePaymentByUser(Auth::user()->id);
        return $this->success(PurchasePaymentDataResource::collection($data));
    }
}
