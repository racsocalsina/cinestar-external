<?php


namespace App\Models\Purchases;


use App\Enums\PurchaseStatus;
use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\MovieTimes\MovieTime;
use App\Models\PaymentGateways\PaymentGatewayInfo;
use App\Models\PurchaseItems\PurchaseItem;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Models\FailedPurchases\FailedPurchase;
use App\Models\SweetsSold\SweetSold;
use App\Models\Tickets\Ticket;
use App\Scopes\TradeNameScope;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PurchaseStatusTransaction;

class Purchase extends Model
{
    use SoftDeletes;

    protected $table = 'purchases';
    protected $fillable = [
        'movie_id',
        'headquarter_id',
        'movie_time_id',
        'amount',
        'uuid',
        'order_number',
        'voucher_type',
        'status',
        'transaction_status',
        'retries',
        'error_event_history',
        'number_tickets',
        'origin',
        'user_id',
        'sold_item_types',
        'guid',
        'confirmed'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function scopeForBO($query)
    {
        return $query->whereNotIn('status', [
            PurchaseStatus::ERROR,
            PurchaseStatus::ERROR_PAYMENT_GATEWAY,
            PurchaseStatus::PENDING,
        ]);
    }

    public function scopeTransactionForBO($query)
    {
        return $query->whereNotIn('transaction_status', [
            PurchaseStatusTransaction::PAYMENT_IN_PROCESS
        ]);
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchase_items() {
        return $this->hasMany(PurchaseItem::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function payment_gateway_info() {
        return $this->hasOne(PaymentGatewayInfo::class);
    }

    public function purchase_voucher() {
        return $this->hasOne(PurchaseVoucher::class);
    }

    public function movie_time()
    {
        return $this->belongsTo(MovieTime::class);
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function sweets_sold() {
        return $this->hasMany(SweetSold::class);
    }

    public function purchase_ticket() {
        return $this->hasOne(PurchaseTicket::class);
    }

    public function purchase_sweet() {
        return $this->hasOne(PurchaseSweet::class);
    }

    public function promotions() {
        return $this->hasMany(PurchasePromotion::class);
    }
}
