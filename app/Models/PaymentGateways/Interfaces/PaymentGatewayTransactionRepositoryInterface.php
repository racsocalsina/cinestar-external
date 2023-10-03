<?php


namespace App\Models\PaymentGateways\Interfaces;


interface PaymentGatewayTransactionRepositoryInterface
{
    public function create(array $body);
}
