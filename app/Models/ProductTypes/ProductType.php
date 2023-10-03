<?php

namespace App\Models\ProductTypes;

use App\Models\Headquarters\Headquarter;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = ['id'];

    public function headquarters()
    {
        return $this->belongsToMany(Headquarter::class, 'headquarter_product_type');
    }
}
