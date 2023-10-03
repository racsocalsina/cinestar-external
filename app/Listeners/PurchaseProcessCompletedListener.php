<?php

namespace App\Listeners;

use App\Events\BillingProcessCompleted;
use App\Events\SendPurchaseEmailCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Enums\PurchaseStatusTransaction;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PurchaseProcessCompletedListener implements ShouldQueue
{
    private PurchaseRepositoryInterface $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function handle($event)
    {
        // Aquí verificamos si ambos eventos han sido disparados, es decir, si ambos trabajos han terminado
        /*if ($event instanceof BillingProcessCompleted) {
            $purchaseId = intval($event->purchaseId);
            $data= $this->failedPurchaseRepository->getByPurchase($purchaseId);
            $savedValue = $data->completed_events ? json_decode($data->completed_events, true) : [];
            if (isset($savedValue['billingProcess']) && $savedValue['billingProcess'] === "completed" &&
                isset($savedValue['sendPurchaseEmail']) && $savedValue['sendPurchaseEmail'] === "completed") {
                // Ambos elementos están presentes y tienen el estado 'true'
                // Realizar la acción correspondiente aquí (por ejemplo, actualizar el estado a 'PURCHASE_COMPLETED')
                $failedPurchase = ['status' => FailedPurchaseStatus::PURCHASE_COMPLETED];
                $this->failedPurchaseRepository->update($purchaseId, $failedPurchase);
            }
        }*/

        if ($event instanceof SendPurchaseEmailCompleted) {
            $purchaseId = intval($event->purchaseId);
            $data = ['transaction_status' => PurchaseStatusTransaction::TICKET_SENT];
            $this->purchaseRepository->updateStatusTransaction($purchaseId, $data);
        }
    }
}