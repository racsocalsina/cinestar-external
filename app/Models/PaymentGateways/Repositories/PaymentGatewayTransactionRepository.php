<?php


namespace App\Models\PaymentGateways\Repositories;


use App\Models\PaymentGateways\Interfaces\PaymentGatewayTransactionRepositoryInterface;
use App\Models\PaymentGateways\PaymentGatewayTransaction;

class PaymentGatewayTransactionRepository implements PaymentGatewayTransactionRepositoryInterface
{
    private PaymentGatewayTransaction $model;

    public function __construct(PaymentGatewayTransaction $model)
    {
        $this->model = $model;
    }

    public function create(array $body)
    {
        return $this->model->create($body);
    }
}
