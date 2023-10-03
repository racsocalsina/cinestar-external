<?php


namespace App\Models\Purchases\Repositories\Interfaces;


use App\Models\PurchaseVoucher\PurchaseVoucher;

interface PurchaseInternalRepositoryInterface
{
    public function sendPurchaseDataToInternal(PurchaseVoucher $purchaseVoucher);
}
