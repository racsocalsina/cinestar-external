<?php

namespace App\Models\MovieTimes;

use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\Rooms\Room;
use App\Scopes\TradeNameScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use  App\Models\MovieTimeTariffs\MovieTimeTariff;

class MovieTime extends Model
{
    use SoftDeletes;
    protected $table = 'movie_times';
    protected $fillable = [
        'id',
        'room_id',
        'movie_id',
        'headquarter_id',
        'remote_funkey',
        'fun_nro',
        'start_at',
        'date_start',
        'time_start',
        'is_presale',
        'planner_graph',
        'planner_meta',
        'capacity',
        'tickets_sold',
        'is_numerated',
        'active',
        'deleted_at',
    ];

    protected $dates = ['start_at'];

    protected static function booted()
    {
        static::addGlobalScope(new TradeNameScope);
    }

    public function getFullStartAtAttribute()
    {
        return $this->date_start . ' ' . $this->time_start;
    }

    public function headquarter() {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function movie_time_tariffs()
    {
        return $this->hasMany(MovieTimeTariff::class, 'movie_time_id', 'id');
    }

    public function scopeFilterByStartDate($query, $startDate = null)
    {
 
        $today = now()->format('Y-m-d');

        if ($startDate == $today) {
            return $query->where('active', 1)
                        ->where('start_at', '>=', now()->format('Y-m-d H:i:s'))
                        ->whereRaw('DATE(start_at) = DATE(?)', [$startDate])
                        ->orderBy('time_start');
        } else {
            return $query->where('active', 1)
                        ->whereDate('start_at', '>=', $startDate)
                        ->whereRaw('DATE(start_at) = DATE(?)', [$startDate])
                        ->orderBy('time_start');
        }
    }
}
