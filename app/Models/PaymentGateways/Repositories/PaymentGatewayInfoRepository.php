<?php


namespace App\Models\PaymentGateways\Repositories;


use App\Models\PaymentGateways\Interfaces\PaymentGatewayInfoRepositoryInterface;
use App\Models\PaymentGateways\PaymentGatewayInfo;

class PaymentGatewayInfoRepository implements PaymentGatewayInfoRepositoryInterface
{
    private PaymentGatewayInfo $model;

    public function __construct(PaymentGatewayInfo $model)
    {
        $this->model = $model;
    }

    public function create(array $body)
    {
        return $this->model->create($body);
    }

    public function deleteByPurchase($purchaseId)
    {
        $this->model->where('purchase_id', $purchaseId)->delete();
    }
}
