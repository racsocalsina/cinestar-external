<?php


namespace App\Models\MovieTimeTariffs;


use App\Models\MovieTariff\MovieTariff;
use App\Models\MovieTimes\MovieTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovieTimeTariff extends Model
{
    use SoftDeletes;
    protected $table = 'movie_time_tariff';

    protected $fillable = [
        'movie_time_id',
        'movie_tariff_id',
        'online_price',
        'is_presale',
        'remote_id',
        'active',
        'deleted_at',
    ];

    public function movie_time() {
        return $this->belongsTo(MovieTime::class, 'movie_time_id');
    }

    public function movie_tariff() {
        return $this->belongsTo(MovieTariff::class, 'movie_tariff_id');
    }

    public function scopeVisibleTariffs($query)
    {
        return $query->whereHas('movie_tariff', function ($q) {
            $q->visible();
        });
    }

}
