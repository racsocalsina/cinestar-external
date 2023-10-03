<?php


namespace App\Models\PaymentGateways;


use Illuminate\Database\Eloquent\Model;

class PaymentGatewayTransaction extends Model
{
    protected $table = 'payment_gateway_transaction';
    protected $guarded = ['id'];
}
