<?php


namespace App\Models\SweetsSold;


use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Products\Product;
use App\Models\PurchaseItems\PurchaseItem;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\Purchases\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SweetSold extends Model
{
    use SoftDeletes;

    protected $table = 'sweets_sold';
    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class, 'sweet_id');
    }

    public function headquarter_product() {
        return $this->belongsTo(HeadquarterProduct::class, 'headquarter_product_id');
    }

    public function product_by_code() {
        return $this->belongsTo(Product::class, 'code', 'code');
    }

    public function purchaseItem() {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function promotion() {
        return $this->belongsTo(PurchasePromotion::class, 'purchase_promotion_id');
    }
}
