<?php

namespace App;

use App\Models\Customers\Customer;
use App\Models\Purchases\Purchase;
use App\Scopes\TradeNameScope;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'type_user',
        'remember_token',
        'resetPassword',
        'trade_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }

    public function getEmailAttribute() {
        return $this->username;
    }

    public function setEmailAttribute($valor)
    {
        $this->attributes['username'] = strtolower($valor);
    }

    public static function generarVerificationToken()
    {
        return str_random(40);
    }

    public function username()
    {
        return 'username';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id', 'user_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function cards()
    {
        return $this->hasMany(\App\Models\Cards\Card::class);
    }
}
