<?php

namespace App\Models\Customers;

use App\User;
use Carbon\Carbon;
use App\Enums\GlobalEnum;
use App\Scopes\TradeNameScope;
use App\Models\Products\Product;
use App\Models\Purchases\Purchase;
use App\Models\Ubigeo\UbDepartment;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsersPartners\UserPartner;
use App\Models\PointsHistory\PointHistory;
use App\Models\TicketPromotions\TicketPromotion;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'document_type',
        'document_number',
        'cellphone',
        'birthdate',
        'user_id',
        'department_id',
        'image_path',
        'trade_name',
        'socio_cod',
        'status',
        'registration_date'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->lastname}";
    }

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_partner()
    {
        return $this->belongsTo(UserPartner::class, 'document_number', 'socdni');
    }

    public function department()
    {
        return $this->belongsTo(UbDepartment::class, 'department_id');
    }

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ? config('constants.path_images') . env('BUCKET_ENV') . GlobalEnum::CUSTOMERS_FOLDER . "/" . $this->attributes['image_path'] : null;
    }

    public function product_favorites()
    {
        return $this->belongsToMany(Product::class, 'customer_product_favorite');
    }

    public function user_partner_cod()
    {
        return $this->belongsTo(UserPartner::class, 'socio_cod', 'soccod');
    }

    public function points_history()
    {
        return $this->belongsTo(PointHistory::class, 'socio_cod', 'soccod');
    }

    public function today_birthday($movie_time)
    {
        if ($this->is_birthday($movie_time)) {
            $purchases = Purchase::where('user_id', $this->user->id)
                ->where('movie_time_id', $movie_time->id)
                ->where('confirmed', true)
                ->whereHas('promotions', function ($query) {
                    $query->where('replace_type', TicketPromotion::class);
                })
                ->get();

            foreach ($purchases as $purchase) {
                $purchase_pomotions = $purchase->promotions->where('replace_type', TicketPromotion::class);
                foreach ($purchase_pomotions as $purchase_pomotion) {
                    if ($purchase_pomotion->replacement->is_birthday) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function is_birthday($movie_time)
    {
        $date = Carbon::parse($movie_time->date_start);
        $birthdate = $this->birthdate;
        return $date->isBirthday($birthdate);
    }
}
