<?php


namespace App\Models\HeadquarterProducts;


use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class HeadquarterProduct extends Model
{
    protected $table = 'headquarter_product';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
