<?php

namespace App\Models\PurchasePromotion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePromotion extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'replace',
        'purchase_id',
        'qty',
        'codes'
    ];

    protected $casts = [
      'codes' => 'array'
    ];

    public function replacement()
    {
        return $this->morphTo('replace');
    }
}
