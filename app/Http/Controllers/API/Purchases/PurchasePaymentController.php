<?php


namespace App\Http\Controllers\API\Purchases;


use App\Enums\PurchaseStatus;
use App\Enums\SoldItemTypes;
use App\Exceptions\PurchaseExceptionNotCompleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\PurchasePaymentRequest;
use App\Http\Resources\Purchases\PurchasePaymentDataResource;
use App\Jobs\Purchase\PurchaseExceptionNotCompletedJob;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Services\PayU\Shared\Exceptions\PayuException;
use App\Traits\ApiResponser;

class PurchasePaymentController extends Controller
{
    use ApiResponser;

    private PurchaseRepositoryInterface $purchaseRepository;
    private PurchasePaymentRepositoryInterface $purchasePaymentRepository;

    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        PurchasePaymentRepositoryInterface $purchasePaymentRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->purchasePaymentRepository = $purchasePaymentRepository;
    }

    public function __invoke(PurchasePaymentRequest $request)
    {
        $purchaseClone = Purchase::with([
            'headquarter', 'payment_gateway_info',
            'movie.gender', 'tickets.movie_time_tariff.movie_tariff',
            'sweets_sold.product', 'purchase_ticket', 'purchase_sweet'
        ])
            ->where('purchases.id', $request->purchase_id)
            ->first();

        $purchase = Purchase::find($request->purchase_id);

        try {
            $response = $this->purchasePaymentRepository->pay($purchase, $request->all());
        } catch (PayuException $payuException) {

            // Solo entrará cuando se genera un error (validación) retornado por la pasarela de pago (tarjeta denegada, fondos insuficientes, etc)
            $this->purchaseRepository->updateAsError(PurchaseStatus::ERROR_PAYMENT_GATEWAY, $purchase, null, $payuException);
            $response = $this->errorResponse(['message' => $payuException->getMessage(), 'dev' => $payuException], 400, $payuException);

        } catch (\Exception $exception) {

            // Controlar este error
            $data = [ 'error_event_history' => json_encode([PurchaseStatus::ERROR => 'true'])];
            $this->purchaseRepository->updateStatusTransaction($purchase->id, $data);
            $this->purchaseRepository->updateAsError(PurchaseStatus::ERROR, $purchase, null, $exception);
            // retornar 200
            $response = $this->getPurchasePaymentData($purchaseClone);
        }

        return $response;
    }

    private function getPurchasePaymentData($purchase)
    {
        $purchase->status = PurchaseStatus::CONFIRMED;

        $array = [];
        $purchaseTicketsExists = $purchase->purchase_ticket ? $purchase->purchase_ticket->count() > 0 : false;
        $purchaseSweetsExists =$purchase->purchase_sweet ? $purchase->purchase_sweet->count() > 0 : false;

        if ($purchaseTicketsExists)
            array_push($array, SoldItemTypes::TICKET);

        if ($purchaseSweetsExists)
            array_push($array, SoldItemTypes::SWEET);

        $purchase->sold_item_types = implode(',', $array);

        return (new PurchasePaymentDataResource($purchase))->setAction('show', true);
    }

}
