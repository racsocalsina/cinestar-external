<?php


namespace App\Models\CustomerProductFavorites;


use Illuminate\Database\Eloquent\Model;

class CustomerProductFavorite extends Model
{
    protected $table = 'customer_product_favorite';
    protected $guarded = ['id'];
    public $timestamps = false;
}
