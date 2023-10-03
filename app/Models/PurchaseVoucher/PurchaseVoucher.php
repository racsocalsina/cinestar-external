<?php

namespace App\Models\PurchaseVoucher;

use App\Models\Purchases\Purchase;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use Illuminate\Database\Eloquent\Model;

class PurchaseVoucher extends Model
{
    protected $table = 'purchase_voucher';
    protected $fillable = [
        'purchase_id',
        'purchase_sweet_id',
        'purchase_ticket_id',
        'internal_serial_number',
        'serial_number',
        'document_number',
        'headquarter_id',
        'date_issue',
        'purchase_order_number',
        'external_id',
        'hash',
        'link_xml',
        'link_pdf',
        'link_cdr',
        'send_fe',
        'request',
        'response'
    ];

    protected $dates = ['date_issue'];

    public function getPurchaseNumberAttribute()
    {
        return "{$this->serial_number}-{$this->document_number}";
    }

    public function getCodeAttribute()
    {
        return "{$this->internal_serial_number}-{$this->document_number}";
    }

    public function purchase_ticket() {
        return $this->belongsTo(PurchaseTicket::class);
    }

    public function purchase_sweet() {
        return $this->belongsTo(PurchaseSweet::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }
}
