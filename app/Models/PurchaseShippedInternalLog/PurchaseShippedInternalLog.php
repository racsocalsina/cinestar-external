<?php

namespace App\Models\PurchaseShippedInternalLog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseShippedInternalLog extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'request'];


}
