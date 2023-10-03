<?php


namespace App\Models\PurchaseErrors\Repositories;


use App\Models\PurchaseErrors\PurchaseError;
use App\Models\PurchaseErrors\Repositories\Interfaces\PurchaseErrorRepositoryInterface;

class PurchaseErrorRepository implements PurchaseErrorRepositoryInterface
{
    public function create($purchaseId, $status, $error)
    {
        PurchaseError::create([
            'purchase_id' => $purchaseId,
            'status' => $status,
            'error' => $error
        ]);
    }
}
