<?php


namespace App\Models\MovieTariff;


use App\Models\MovieTimeTariffs\MovieTimeTariff;
use Illuminate\Database\Eloquent\Model;

class MovieTariff extends Model
{
    protected $table = 'movie_tariffs';

    protected $fillable = [
        'name',
        'remote_funtar'
    ];

    public function movie_time_tariffs() {
        return $this->hasMany(MovieTimeTariff::class, 'movie_tarrif_id', 'id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
