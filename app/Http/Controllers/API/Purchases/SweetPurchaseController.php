<?php


namespace App\Http\Controllers\API\Purchases;


use App\Helpers\FunctionHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Purchase\SweetPurchaseRequest;
use App\Http\Requests\Purchase\UpdateSweetPurchaseRequest;
use App\Http\Resources\Purchases\PurchaseResource;
use App\Jobs\SendErrorEmail;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\SweetPurchaseRepositoryInterface;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Support\Facades\DB;

class SweetPurchaseController extends ApiController
{
    private $purchaseRepository;
    private $userRepository;

    public function __construct(
        SweetPurchaseRepositoryInterface $purchaseRepository,
        UserRepositoryInterface          $userRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->userRepository = $userRepository;
    }

    public function store(SweetPurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $purchase = $this->purchaseRepository->create($request);
            $purchase['antifraud_data'] = $this->userRepository->getAntifraudData();
            $data = new PurchaseResource($purchase);
            $response = $this->successResponse($data);
        } catch (\Exception $exception) {
            $message = 'Error al registrar la compra de dulceria. Inténtelo nuevamente.';
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            FunctionHelper::sendErrorMail($exceptionDto);
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function update(Purchase $purchase, UpdateSweetPurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $purchase = $this->purchaseRepository->update($purchase->id, $request);
            $data = new PurchaseResource($purchase);
            $response = $this->successResponse($data);
        } catch (\Exception $exception) {
            $message = 'Error al actualizar la compra de dulceria. Inténtelo nuevamente.';
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

}
