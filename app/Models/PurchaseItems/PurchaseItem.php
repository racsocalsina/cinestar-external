<?php


namespace App\Models\PurchaseItems;


use App\Models\SweetsSold\SweetSold;
use App\Models\Tickets\Ticket;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';
    protected $fillable = [
        'original_amount',
        'paid_amount',
        'purchase_id',
        'purchase_ticket_id',
        'purchase_sweet_id'
    ];

    public function ticket() {
        return $this->hasOne(Ticket::class);
    }

    public function sweet() {
        return $this->hasOne(SweetSold::class);
    }
}
