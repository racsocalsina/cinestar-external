<?php


namespace App\Models\TypePaymentMethods;


use Illuminate\Database\Eloquent\Model;

class TypePaymentMethod extends Model
{
    protected $table = 'types_payment_method';
    protected $fillable = [
        'remote_code',
        'name',
        'type_currency',
        'payment_type'
        ];
}
