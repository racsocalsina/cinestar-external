<?php


namespace App\Models\CustomerHeadquarterFavorites;


use Illuminate\Database\Eloquent\Model;

class CustomerHeadquarterFavorite extends Model
{
    protected $table = 'customer_headquarter_favorite';

    protected $fillable = ['customer_id', 'headquarter_id'];
}
