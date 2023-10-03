<?php


namespace App\Models\PaymentGateways\Interfaces;


interface PaymentGatewayInfoRepositoryInterface
{
    public function create(array $body);
    public function deleteByPurchase($purchaseId);
}
