<?php


namespace App\Models\PurchaseLogs;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseLog extends Model
{
    protected $table = 'purchase_logs';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $incrementing = false;

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }
}
