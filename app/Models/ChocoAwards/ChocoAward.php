<?php


namespace App\Models\ChocoAwards;



use App\Enums\GlobalEnum;
use App\Models\Products\Product;
use App\Models\PurchasePromotion\PurchasePromotion;
use Illuminate\Database\Eloquent\Model;

class ChocoAward extends Model
{
    protected $guarded = ['id'];

    public function getImagePathAttribute()
    {
        return $this->image ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::CHOCO_AWARDS_FOLDER."/".$this->image : null;
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase_promotion()
    {
        return $this->morphOne(PurchasePromotion::class, 'replace');
    }
}
