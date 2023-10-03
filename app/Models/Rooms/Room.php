<?php

namespace App\Models\Rooms;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'headquarter_id',
        'room_type_id',
        'remote_salkey',
        'room_number',
        'capacity',
        'is_numerate',
        'number_rows',
        'number_columns',
        'number_halls',
        'total_columns',
        'planner_graph',
        'planner_meta',
        'name',
        'active'
    ];

    public function movie_times()
    {
        return $this->hasMany(MovieTime::class, 'room_id');
    }
}
