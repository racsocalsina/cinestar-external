<?php


namespace App\Models\ChocoPromotionProducts;

use App\Models\ChocoPromotions\ChocoPromotion;
use App\Models\Products\Product;
use App\Models\PurchasePromotion\PurchasePromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChocoPromotionProduct extends Model
{
    use SoftDeletes;
    protected $fillable  = ['price', 'discount_rate', 'product_id', 'promotion_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function choco_promotion()
    {
        return $this->belongsTo(ChocoPromotion::class, 'promotion_id');
    }

    public function purchase_promotion()
    {
        return $this->morphOne(PurchasePromotion::class, 'replace');
    }
}
