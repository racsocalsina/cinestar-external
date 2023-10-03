<?php


namespace App\Models\Purchases\Repositories\Interfaces;


interface PurchasePaymentRepositoryInterface
{
    public function pay($purchase, $body, $fromBo = false);
    public function updatePurchaseAsConfirmed($purchase, $paymentGatewayResponse = null): void;
    public function transactionalProcesses();
    public function reserveSerialNumber($salesType, $purchaseTypeData);
}
