<?php


namespace App\Models\Headquarters;

use App\Enums\BusinessName;
use App\Helpers\FunctionHelper;
use App\Models\Cities\City;
use App\Models\CustomerHeadquarterFavorites\CustomerHeadquarterFavorite;
use App\Models\Customers\Customer;
use App\Models\HeadquarterImages\HeadquarterImage;
use App\Models\MovieFormats\MovieFormat;
use App\Models\MovieTimes\MovieTime;
use App\Models\Products\Product;
use App\Models\ProductTypes\ProductType;
use App\Models\SyncLogs\SyncLog;
use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Scopes\TradeNameScope;
use App\Traits\Models\Activatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Headquarter extends Model implements ActivatableInterface
{
    use SoftDeletes, Activatable;

    protected $table = 'headquarters';

    protected $guarded = ['id'];

    protected $hidden = ['pivot', 'password'];

    protected $appends = ['main_photo', 'is_favorite'];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function getMainPhotoAttribute()
    {
        $image = $this->headquarter_images()->where('is_main_image', true)->first();

        if ($image !== null) return $image->path;

        return null;
    }

    public function getPreptoAttribute()
    {
        return trim(BusinessName::getValueByBusinessName($this->business_name) . $this->point_sale);
    }

    public function getIsFavoriteAttribute() {
        $user = FunctionHelper::getApiUser();
        if ($user) {
            $customer = Customer::where('user_id', $user->id)->first();
            return $this->favorite()
                ->where('customer_id', '=', $customer->id)
                ->exists();
        } else {
            return false;
        }
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function headquarter_images()
    {
        return $this->hasMany(HeadquarterImage::class);
    }

    public function movie_formats()
    {
        return $this->belongsToMany(MovieFormat::class, 'headquarter_movie_formats');
    }

    public function favorite() {
        return $this->belongsTo(CustomerHeadquarterFavorite::class, 'id', 'headquarter_id');
    }

    public function movie_times()
    {
        return $this->hasMany(MovieTime::class);
    }

    public function sync_logs()
    {
        return $this->hasMany(SyncLog::class);
    }

    public function product_types()
    {
        return $this->belongsToMany(ProductType::class, 'headquarter_product_type');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'headquarter_product');
    }

    /**
     * Scope for active headquarters
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }
}
