<?php


namespace App\Models\Cities;

use App\Scopes\TradeNameScope;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name', 'trade_name'];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }
}
