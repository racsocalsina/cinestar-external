<?php

namespace App\Models\Products;

use App\Enums\GlobalEnum;
use App\Models\Customers\Customer;
use App\Models\Headquarters\Headquarter;
use App\Models\ProductTypes\ProductType;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public function getImagePathAttribute()
    {
        return $this->image ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::PRODUCTS_FOLDER."/".$this->image : null;
    }

    public function getImage2PathAttribute()
    {
        return $this->image2 ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::PRODUCTS_FOLDER."/".$this->image2 : null;
    }

    public function type() {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function headquarters()
    {
        return $this->belongsToMany(Headquarter::class, 'headquarter_product');
    }

    public function customer_favorites()
    {
        return $this->belongsToMany(Customer::class, 'customer_product_favorite');
    }
}
