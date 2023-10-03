<?php

namespace App\Models\PurchaseCustomerInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseCustomerInfo extends Model
{
    protected $table = 'purchase_customer_info';
    protected $fillable = [
        'purchase_voucher_id',
        'customer_id',
        'type_document',
        'document_number',
        'name',
        'ubigeo',
        'address',
        'email',
        'phone'
    ];
}
