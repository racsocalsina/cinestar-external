<?php

namespace App\Models\TicketPromotions;

use App\Enums\GlobalEnum;
use App\Enums\TariffType;
use App\Helpers\Helper;
use App\Models\Bins\Bin;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTariff\MovieTariff;
use App\Models\Products\Product;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\TicketAwards\TicketAward;
use App\Models\TypePaymentMethods\TypePaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPromotion extends Model
{
    use SoftDeletes;

    protected $table = 'ticket_promotions';
    protected $fillable = [
        'code',
        'name',
        'tickets_number',
        'promo_tickets_number',
        'tariff_type',
        'price_second_ticket',
        'discount_rate',
        'product_code',
        'price_ticket',
        'price_product',
        'membership_card_required',
        'is_block_3d',
        'is_block_1s',
        'start_date',
        'end_date',
        'is_block_sunday',
        'is_block_monday',
        'is_block_tuesday',
        'is_block_wednesday',
        'is_block_thursday',
        'is_block_friday',
        'is_block_saturday',
        'trade_name',
        'type_payment_method_id',
        'headquarter_id',
        'max_num_tickets',
        'promotion_type',
        'movie_chain'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function getImagePathAttribute()
    {
        return $this->image ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::TICKET_PROMOTION_FOLDER."/".$this->image : null;
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function movie_tariff()
    {
        return $this->belongsTo(MovieTariff::class, 'tariff_type', 'remote_funtar');
    }

    public function payment_method_type()
    {
        return $this->belongsTo(TypePaymentMethod::class, 'type_payment_method_id');
    }

    public function award()
    {
        return $this->hasOne(TicketAward::class);
    }

    public function purchase_promotion()
    {
        return $this->morphOne(PurchasePromotion::class, 'replace');
    }

    public function getIsBirthdayAttribute()
    {
        return $this->tickets_number === 0 && !$this->tariff_type;
    }

    public function getTicketQtyAttribute()
    {
        return $this->isBirthday ? $this->max_num_tickets : $this->tickets_number;
    }

    public function getTicketsMaxAttribute()
    {
        return $this->max_num_tickets ? $this->max_num_tickets : null;
    }

    public function getTypeTariffAttribute()
    {
        return $this->isBirthday ? TariffType::ADULTO : $this->tariff_type;
    }

    public function validPaymentMethod($bin)
    {
        return Bin::where('tpm_code', $this->payment_method_type->remote_code)
            ->where('bin', $bin)
            ->count() > 0;
    }

    public function validByPromotion($movie_time)
    {
        return self::where('id', $this->id)->with(['product', 'promotion'])
            ->where(function ($query) {
                $query->where('movie_chain', Helper::getTradeNameHeader())
                    ->orwhereNull('movie_chain');
            })->where(function ($query) use ($movie_time) {
                $query->where('headquarter_id', $movie_time->headquarter_id)
                    ->orwhereNull('headquarter_id');
            })->when($movie_time->movie->is_3d == 1, function ($query) use ($movie_time) {
                return $query->where('is_block_3d', 0);

            })->when(Carbon::parse($movie_time->date_start) < $movie_time->movie->last_premier_date, function ($query) use ($movie_time) {
                return $query->where('is_block_1s', 0);
            })->where('start_date', '<=', Carbon::parse($movie_time->date_start))
            ->where('end_date', '>=', Carbon::parse($movie_time->date_start))
            ->where(function ($query) use ($movie_time) {
                $today = Carbon::parse($movie_time->date_start)->formatLocalized('%A');
                if ($today == 'Sunday') {
                    $query->where('is_block_sunday', 0);
                } else if ($today == 'Monday') {
                    $query->where('is_block_monday', 0);
                } else if ($today == 'Tuesday') {
                    $query->where('is_block_tuesday', 0);
                } else if ($today == 'Wednesday') {
                    $query->where('is_block_wednesday', 0);
                } else if ($today == 'Thursday') {
                    $query->where('is_block_thursday', 0);
                } else if ($today == 'Friday') {
                    $query->where('is_block_friday', 0);
                } else if ($today == 'Saturday') {
                    $query->where('is_block_saturday', 0);
                }
            })->count();

    }
}
