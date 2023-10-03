<?php


namespace App\Models\Purchases\Repositories\Interfaces;


interface BillingRepositoryInterface
{
    public function callApi($purchaseVoucher);
}
