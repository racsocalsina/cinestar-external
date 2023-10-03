<?php


namespace App\Models\PurchaseTickets;


use App\Models\Purchases\Purchase;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use Illuminate\Database\Eloquent\Model;

class PurchaseTicket extends Model
{
    protected $table = 'purchase_tickets';
    protected $guarded = ['id'];
    protected $dates = ['function_date'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function purchase_voucher() {
        return $this->hasOne(PurchaseVoucher::class);
    }
}
