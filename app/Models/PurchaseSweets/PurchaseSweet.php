<?php


namespace App\Models\PurchaseSweets;


use App\Models\Purchases\Purchase;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use Illuminate\Database\Eloquent\Model;

class PurchaseSweet extends Model
{
    protected $table = 'purchase_sweets';
    protected $guarded = ['id'];
    protected $dates = ['pickup_date'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function purchase_voucher() {
        return $this->hasOne(PurchaseVoucher::class);
    }
}
