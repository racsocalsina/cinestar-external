<?php


namespace App\Models\TicketAwards;


use App\Enums\GlobalEnum;
use App\Models\Products\Product;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Database\Eloquent\Model;

class TicketAward extends Model
{
    protected $guarded = ['id'];

    public function getImagePathAttribute()
    {
        return $this->image ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::TICKET_AWARDS_FOLDER."/".$this->image : null;
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function promotion() {
        return $this->belongsTo(TicketPromotion::class, 'ticket_promotion_id');
    }

    public function purchase_promotion()
    {
        return $this->morphOne(PurchasePromotion::class, 'replace');
    }
}
