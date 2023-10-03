<?php

namespace App\Models\Movies;

use App\Enums\GlobalEnum;
use App\Models\Countries\Country;
use App\Models\MovieGenders\MovieGender;
use App\Models\MovieTimes\MovieTime;
use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\Models\Activatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model implements ActivatableInterface
{
    use Activatable;

    protected $table = 'movies';
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];
    protected $fillable = [
        'code', 'name', 'summary', 'duration_in_minutes',
        'type_of_censorship', 'premier_date', 'status', 'exclude_igv',
        'exclude_city_tax', 'country_id', 'movie_gender_id', 'is_3d'
    ];

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ? config('constants.path_images').env('BUCKET_ENV').GlobalEnum::MOVIES_FOLDER."/".$this->attributes['image_path'] : null;
    }

    public function gender() {
        return $this->belongsTo(MovieGender::class, 'movie_gender_id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function movie_times()
    {
        return $this->hasMany(MovieTime::class, 'movie_id', 'id');
    }

    public function getLastPremierDateAttribute(){
        return Carbon::parse($this->premier_date)->addDay(6);
    }

}
