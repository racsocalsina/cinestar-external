<?php


namespace App\Models\PurchaseErrors;


use App\Models\Purchases\Purchase;
use Illuminate\Database\Eloquent\Model;

class PurchaseError extends Model
{
    public $guarded = ['id'];

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }
}
