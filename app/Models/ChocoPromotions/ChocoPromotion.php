<?php


namespace App\Models\ChocoPromotions;


use App\Enums\GlobalEnum;
use App\Helpers\Helper;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use App\Models\Headquarters\Headquarter;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\Purchases\Purchase;
use App\Models\TypePaymentMethods\TypePaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChocoPromotion extends Model
{
    use SoftDeletes;
    protected $fillable  = ['code', 'name', 'is_presale', 'start_date', 'end_date', 'discount_rate', 'membership_card_required',
        'type_payment_method_id', 'headquarter_id', 'applies_to_all', 'movie_chain',
        'is_block_sunday',
        'is_block_monday',
        'is_block_tuesday',
        'is_block_wednesday',
        'is_block_thursday',
        'is_block_friday',
        'is_block_saturday',
        'promotion_type'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function getImagePathAttribute()
    {
        return $this->image ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::CHOCO_PROMOTION_FOLDER."/".$this->image : null;
    }

    public function headquarter() {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    public function payment_method_type() {
        return $this->belongsTo(TypePaymentMethod::class, 'type_payment_method_id');
    }

    public function products() {
        return $this->hasMany(ChocoPromotionProduct::class, 'promotion_id', 'id');
    }

    public function purchase_promotion()
    {
        return $this->morphOne(PurchasePromotion::class, 'replace');
    }

    public function validByPromotion($purchase_id, $headquarter_id)
    {
        $today = $purchase_id ? Carbon::parse(Purchase::find($purchase_id)->movie_time->date_start) : Carbon::now();
        $chain =  Helper::getTradeNameHeader() == 'CINESTAR' ? 1 : 0;
        return self::where('id', $this->id)->with(['product', 'promotion'])
            ->where(function ($query) use ($chain) {
                $query->where('movie_chain', $chain)
                    ->orwhereNull('movie_chain');
            })->where(function ($query) use ($headquarter_id) {
                $query->where('headquarter_id', $headquarter_id)
                    ->orwhereNull('headquarter_id');
            })->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where(function ($query) use ($today) {
                $today = $today->formatLocalized('%A');
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
