<?php

namespace App\Models\Banners;

use App\Enums\GlobalEnum;
use App\Scopes\TradeNameScope;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $fillable = ['link', 'path', 'type', 'trade_name', 'page'];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function getPathAttribute()
    {
        return $this->attributes['path'] ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::BANNERS_FOLDER."/".$this->attributes['path'] : null;
    }

    public function getImageAttribute()
    {
        return $this->attributes['path'];
    }
}
