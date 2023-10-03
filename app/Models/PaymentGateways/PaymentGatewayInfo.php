<?php


namespace App\Models\PaymentGateways;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewayInfo extends Model
{
    use SoftDeletes;

    protected $table = 'payment_gateway_info';
    protected $guarded = ['id'];

    public function payment_gateway_transaction() {
        return $this->hasOne(PaymentGatewayTransaction::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname;
    }
}
