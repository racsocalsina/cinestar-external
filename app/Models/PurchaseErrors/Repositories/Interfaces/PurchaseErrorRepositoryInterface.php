<?php


namespace App\Models\PurchaseErrors\Repositories\Interfaces;


interface PurchaseErrorRepositoryInterface
{
    public function create($purchaseId, $status, $error);
}
