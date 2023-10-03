<?php


namespace App\Models\HeadquarterMovies;


use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\MovieTimes\MovieTime;
use Illuminate\Database\Eloquent\Model;

class HeadquarterMovie extends Model
{
    protected $table = 'headquarter_movies';

    protected $fillable = [
        'movie_id',
        'headquarter_id',
        'start_date',
        'end_date',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function movie_times() {
        return $this->hasMany(MovieTime::class);
    }
}
