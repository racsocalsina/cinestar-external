<?php

namespace App\Models\MongoMovies;

use Jenssegers\Mongodb\Eloquent\Model;


class MongoPelis extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pelis';
    protected $hidden = ['_id'];
    protected $dates = ['created_at', 'updated_at', 'movie_times.start_at', 'movie_times.start_at'];
    protected $fillable = [
        'id',
        'code',
        'is_3d',
        'name',
        'image_path',
        'url_trailer',
        'summary',
        'duration_in_minutes',
        'type_of_censorship',
        'premier_date',
        'movie_gender_id',
        'movie_gender_name',
        'country_id',
        'country_name',
        'movie_times',
    ];

    function createDemo() {
        $data = '{"id":2280,"code":"002288","name":"EL NIÑO Y EL TIGRE    (HD) (DOB)","is_3d":0,"summary":"Una noche, el pequeño niño huérfano Balmani rescata a un cachorro de tigre de unos despiadados cazadores furtivos. Balmani escapa y emprende un largo y peligroso viaje a un santuario remoto, donde cree que estarán a salvo.","country_id":8,"image_path":"1675271890.jpg","movie_times":[{"city_id":9,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":12},{"city_id":9,"start_at":"2023-02-02 17:45:00.000000","date_start":"2023-02-02","headquarter_id":12},{"city_id":9,"start_at":"2023-02-03 15:30:00.000000","date_start":"2023-02-03","headquarter_id":12},{"city_id":9,"start_at":"2023-02-04 15:30:00.000000","date_start":"2023-02-04","headquarter_id":12},{"city_id":9,"start_at":"2023-02-05 15:30:00.000000","date_start":"2023-02-05","headquarter_id":12},{"city_id":9,"start_at":"2023-02-06 15:30:00.000000","date_start":"2023-02-06","headquarter_id":12},{"city_id":9,"start_at":"2023-02-07 15:30:00.000000","date_start":"2023-02-07","headquarter_id":12},{"city_id":9,"start_at":"2023-02-08 15:30:00.000000","date_start":"2023-02-08","headquarter_id":12},{"city_id":10,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":11},{"city_id":10,"start_at":"2023-02-02 17:00:00.000000","date_start":"2023-02-02","headquarter_id":11},{"city_id":10,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":11},{"city_id":10,"start_at":"2023-02-03 17:00:00.000000","date_start":"2023-02-03","headquarter_id":11},{"city_id":10,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":11},{"city_id":10,"start_at":"2023-02-04 17:00:00.000000","date_start":"2023-02-04","headquarter_id":11},{"city_id":10,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":11},{"city_id":10,"start_at":"2023-02-05 17:00:00.000000","date_start":"2023-02-05","headquarter_id":11},{"city_id":10,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":11},{"city_id":10,"start_at":"2023-02-06 17:00:00.000000","date_start":"2023-02-06","headquarter_id":11},{"city_id":10,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":11},{"city_id":10,"start_at":"2023-02-07 17:00:00.000000","date_start":"2023-02-07","headquarter_id":11},{"city_id":10,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":11},{"city_id":10,"start_at":"2023-02-08 17:00:00.000000","date_start":"2023-02-08","headquarter_id":11},{"city_id":1,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":27},{"city_id":1,"start_at":"2023-02-02 17:45:00.000000","date_start":"2023-02-02","headquarter_id":27},{"city_id":1,"start_at":"2023-02-03 15:45:00.000000","date_start":"2023-02-03","headquarter_id":27},{"city_id":1,"start_at":"2023-02-03 17:45:00.000000","date_start":"2023-02-03","headquarter_id":27},{"city_id":1,"start_at":"2023-02-04 15:45:00.000000","date_start":"2023-02-04","headquarter_id":27},{"city_id":1,"start_at":"2023-02-04 17:45:00.000000","date_start":"2023-02-04","headquarter_id":27},{"city_id":1,"start_at":"2023-02-05 15:45:00.000000","date_start":"2023-02-05","headquarter_id":27},{"city_id":1,"start_at":"2023-02-05 17:45:00.000000","date_start":"2023-02-05","headquarter_id":27},{"city_id":1,"start_at":"2023-02-06 15:45:00.000000","date_start":"2023-02-06","headquarter_id":27},{"city_id":1,"start_at":"2023-02-06 17:45:00.000000","date_start":"2023-02-06","headquarter_id":27},{"city_id":1,"start_at":"2023-02-07 15:45:00.000000","date_start":"2023-02-07","headquarter_id":27},{"city_id":1,"start_at":"2023-02-07 17:45:00.000000","date_start":"2023-02-07","headquarter_id":27},{"city_id":1,"start_at":"2023-02-08 15:45:00.000000","date_start":"2023-02-08","headquarter_id":27},{"city_id":1,"start_at":"2023-02-08 17:45:00.000000","date_start":"2023-02-08","headquarter_id":27},{"city_id":4,"start_at":"2023-02-02 14:50:00.000000","date_start":"2023-02-02","headquarter_id":29},{"city_id":4,"start_at":"2023-02-02 16:50:00.000000","date_start":"2023-02-02","headquarter_id":29},{"city_id":4,"start_at":"2023-02-03 14:50:00.000000","date_start":"2023-02-03","headquarter_id":29},{"city_id":4,"start_at":"2023-02-03 16:50:00.000000","date_start":"2023-02-03","headquarter_id":29},{"city_id":4,"start_at":"2023-02-04 14:50:00.000000","date_start":"2023-02-04","headquarter_id":29},{"city_id":4,"start_at":"2023-02-04 16:50:00.000000","date_start":"2023-02-04","headquarter_id":29},{"city_id":4,"start_at":"2023-02-05 14:50:00.000000","date_start":"2023-02-05","headquarter_id":29},{"city_id":4,"start_at":"2023-02-05 16:50:00.000000","date_start":"2023-02-05","headquarter_id":29},{"city_id":4,"start_at":"2023-02-06 14:50:00.000000","date_start":"2023-02-06","headquarter_id":29},{"city_id":4,"start_at":"2023-02-06 16:50:00.000000","date_start":"2023-02-06","headquarter_id":29},{"city_id":4,"start_at":"2023-02-07 14:50:00.000000","date_start":"2023-02-07","headquarter_id":29},{"city_id":4,"start_at":"2023-02-07 16:50:00.000000","date_start":"2023-02-07","headquarter_id":29},{"city_id":4,"start_at":"2023-02-08 14:50:00.000000","date_start":"2023-02-08","headquarter_id":29},{"city_id":4,"start_at":"2023-02-08 16:50:00.000000","date_start":"2023-02-08","headquarter_id":29},{"city_id":1,"start_at":"2023-02-02 16:00:00.000000","date_start":"2023-02-02","headquarter_id":2},{"city_id":1,"start_at":"2023-02-03 16:00:00.000000","date_start":"2023-02-03","headquarter_id":2},{"city_id":1,"start_at":"2023-02-04 16:00:00.000000","date_start":"2023-02-04","headquarter_id":2},{"city_id":1,"start_at":"2023-02-05 16:00:00.000000","date_start":"2023-02-05","headquarter_id":2},{"city_id":1,"start_at":"2023-02-06 16:00:00.000000","date_start":"2023-02-06","headquarter_id":2},{"city_id":1,"start_at":"2023-02-07 16:00:00.000000","date_start":"2023-02-07","headquarter_id":2},{"city_id":1,"start_at":"2023-02-08 16:00:00.000000","date_start":"2023-02-08","headquarter_id":2},{"city_id":1,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":9},{"city_id":1,"start_at":"2023-02-02 17:30:00.000000","date_start":"2023-02-02","headquarter_id":9},{"city_id":1,"start_at":"2023-02-03 15:45:00.000000","date_start":"2023-02-03","headquarter_id":9},{"city_id":1,"start_at":"2023-02-03 17:30:00.000000","date_start":"2023-02-03","headquarter_id":9},{"city_id":1,"start_at":"2023-02-04 15:45:00.000000","date_start":"2023-02-04","headquarter_id":9},{"city_id":1,"start_at":"2023-02-04 17:30:00.000000","date_start":"2023-02-04","headquarter_id":9},{"city_id":1,"start_at":"2023-02-05 15:45:00.000000","date_start":"2023-02-05","headquarter_id":9},{"city_id":1,"start_at":"2023-02-05 17:30:00.000000","date_start":"2023-02-05","headquarter_id":9},{"city_id":1,"start_at":"2023-02-06 15:45:00.000000","date_start":"2023-02-06","headquarter_id":9},{"city_id":1,"start_at":"2023-02-06 17:30:00.000000","date_start":"2023-02-06","headquarter_id":9},{"city_id":1,"start_at":"2023-02-07 15:45:00.000000","date_start":"2023-02-07","headquarter_id":9},{"city_id":1,"start_at":"2023-02-07 17:30:00.000000","date_start":"2023-02-07","headquarter_id":9},{"city_id":1,"start_at":"2023-02-08 15:45:00.000000","date_start":"2023-02-08","headquarter_id":9},{"city_id":1,"start_at":"2023-02-08 17:30:00.000000","date_start":"2023-02-08","headquarter_id":9},{"city_id":1,"start_at":"2023-02-02 14:15:00.000000","date_start":"2023-02-02","headquarter_id":8},{"city_id":1,"start_at":"2023-02-02 16:00:00.000000","date_start":"2023-02-02","headquarter_id":8},{"city_id":1,"start_at":"2023-02-03 14:15:00.000000","date_start":"2023-02-03","headquarter_id":8},{"city_id":1,"start_at":"2023-02-03 16:00:00.000000","date_start":"2023-02-03","headquarter_id":8},{"city_id":1,"start_at":"2023-02-04 14:15:00.000000","date_start":"2023-02-04","headquarter_id":8},{"city_id":1,"start_at":"2023-02-04 16:00:00.000000","date_start":"2023-02-04","headquarter_id":8},{"city_id":1,"start_at":"2023-02-05 14:15:00.000000","date_start":"2023-02-05","headquarter_id":8},{"city_id":1,"start_at":"2023-02-05 16:00:00.000000","date_start":"2023-02-05","headquarter_id":8},{"city_id":1,"start_at":"2023-02-06 14:15:00.000000","date_start":"2023-02-06","headquarter_id":8},{"city_id":1,"start_at":"2023-02-06 16:00:00.000000","date_start":"2023-02-06","headquarter_id":8},{"city_id":1,"start_at":"2023-02-07 14:15:00.000000","date_start":"2023-02-07","headquarter_id":8},{"city_id":1,"start_at":"2023-02-07 16:00:00.000000","date_start":"2023-02-07","headquarter_id":8},{"city_id":1,"start_at":"2023-02-08 14:15:00.000000","date_start":"2023-02-08","headquarter_id":8},{"city_id":1,"start_at":"2023-02-08 16:00:00.000000","date_start":"2023-02-08","headquarter_id":8},{"city_id":1,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":5},{"city_id":1,"start_at":"2023-02-02 17:30:00.000000","date_start":"2023-02-02","headquarter_id":5},{"city_id":1,"start_at":"2023-02-03 15:45:00.000000","date_start":"2023-02-03","headquarter_id":5},{"city_id":1,"start_at":"2023-02-03 17:30:00.000000","date_start":"2023-02-03","headquarter_id":5},{"city_id":1,"start_at":"2023-02-04 15:45:00.000000","date_start":"2023-02-04","headquarter_id":5},{"city_id":1,"start_at":"2023-02-05 15:45:00.000000","date_start":"2023-02-05","headquarter_id":5},{"city_id":1,"start_at":"2023-02-06 15:45:00.000000","date_start":"2023-02-06","headquarter_id":5},{"city_id":1,"start_at":"2023-02-07 15:45:00.000000","date_start":"2023-02-07","headquarter_id":5},{"city_id":1,"start_at":"2023-02-08 15:45:00.000000","date_start":"2023-02-08","headquarter_id":5},{"city_id":6,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":4},{"city_id":6,"start_at":"2023-02-02 17:00:00.000000","date_start":"2023-02-02","headquarter_id":4},{"city_id":6,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":4},{"city_id":6,"start_at":"2023-02-03 17:00:00.000000","date_start":"2023-02-03","headquarter_id":4},{"city_id":6,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":4},{"city_id":6,"start_at":"2023-02-04 17:00:00.000000","date_start":"2023-02-04","headquarter_id":4},{"city_id":6,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":4},{"city_id":6,"start_at":"2023-02-05 17:00:00.000000","date_start":"2023-02-05","headquarter_id":4},{"city_id":6,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":4},{"city_id":6,"start_at":"2023-02-06 17:00:00.000000","date_start":"2023-02-06","headquarter_id":4},{"city_id":6,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":4},{"city_id":6,"start_at":"2023-02-07 17:00:00.000000","date_start":"2023-02-07","headquarter_id":4},{"city_id":6,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":4},{"city_id":6,"start_at":"2023-02-08 17:00:00.000000","date_start":"2023-02-08","headquarter_id":4},{"city_id":1,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":6},{"city_id":1,"start_at":"2023-02-02 17:30:00.000000","date_start":"2023-02-02","headquarter_id":6},{"city_id":1,"start_at":"2023-02-03 15:45:00.000000","date_start":"2023-02-03","headquarter_id":6},{"city_id":1,"start_at":"2023-02-03 17:30:00.000000","date_start":"2023-02-03","headquarter_id":6},{"city_id":1,"start_at":"2023-02-04 15:45:00.000000","date_start":"2023-02-04","headquarter_id":6},{"city_id":1,"start_at":"2023-02-04 17:30:00.000000","date_start":"2023-02-04","headquarter_id":6},{"city_id":1,"start_at":"2023-02-05 15:45:00.000000","date_start":"2023-02-05","headquarter_id":6},{"city_id":1,"start_at":"2023-02-05 17:30:00.000000","date_start":"2023-02-05","headquarter_id":6},{"city_id":1,"start_at":"2023-02-06 15:45:00.000000","date_start":"2023-02-06","headquarter_id":6},{"city_id":1,"start_at":"2023-02-06 17:30:00.000000","date_start":"2023-02-06","headquarter_id":6},{"city_id":1,"start_at":"2023-02-07 15:45:00.000000","date_start":"2023-02-07","headquarter_id":6},{"city_id":1,"start_at":"2023-02-07 17:30:00.000000","date_start":"2023-02-07","headquarter_id":6},{"city_id":1,"start_at":"2023-02-08 15:45:00.000000","date_start":"2023-02-08","headquarter_id":6},{"city_id":1,"start_at":"2023-02-08 17:30:00.000000","date_start":"2023-02-08","headquarter_id":6},{"city_id":1,"start_at":"2023-02-02 16:50:00.000000","date_start":"2023-02-02","headquarter_id":16},{"city_id":1,"start_at":"2023-02-03 15:00:00.000000","date_start":"2023-02-03","headquarter_id":16},{"city_id":1,"start_at":"2023-02-03 16:50:00.000000","date_start":"2023-02-03","headquarter_id":16},{"city_id":1,"start_at":"2023-02-04 15:00:00.000000","date_start":"2023-02-04","headquarter_id":16},{"city_id":1,"start_at":"2023-02-05 15:00:00.000000","date_start":"2023-02-05","headquarter_id":16},{"city_id":1,"start_at":"2023-02-06 15:00:00.000000","date_start":"2023-02-06","headquarter_id":16},{"city_id":1,"start_at":"2023-02-07 15:00:00.000000","date_start":"2023-02-07","headquarter_id":16},{"city_id":1,"start_at":"2023-02-08 15:00:00.000000","date_start":"2023-02-08","headquarter_id":16},{"city_id":6,"start_at":"2023-02-02 15:30:00.000000","date_start":"2023-02-02","headquarter_id":15},{"city_id":6,"start_at":"2023-02-02 17:20:00.000000","date_start":"2023-02-02","headquarter_id":15},{"city_id":6,"start_at":"2023-02-03 15:30:00.000000","date_start":"2023-02-03","headquarter_id":15},{"city_id":6,"start_at":"2023-02-04 15:30:00.000000","date_start":"2023-02-04","headquarter_id":15},{"city_id":6,"start_at":"2023-02-05 15:30:00.000000","date_start":"2023-02-05","headquarter_id":15},{"city_id":6,"start_at":"2023-02-06 15:30:00.000000","date_start":"2023-02-06","headquarter_id":15},{"city_id":6,"start_at":"2023-02-07 15:30:00.000000","date_start":"2023-02-07","headquarter_id":15},{"city_id":6,"start_at":"2023-02-08 15:30:00.000000","date_start":"2023-02-08","headquarter_id":15},{"city_id":1,"start_at":"2023-02-02 15:00:00.000000","date_start":"2023-02-02","headquarter_id":16},{"city_id":4,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":14},{"city_id":4,"start_at":"2023-02-02 17:00:00.000000","date_start":"2023-02-02","headquarter_id":14},{"city_id":4,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":14},{"city_id":4,"start_at":"2023-02-03 17:00:00.000000","date_start":"2023-02-03","headquarter_id":14},{"city_id":4,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":14},{"city_id":4,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":14},{"city_id":4,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":14},{"city_id":4,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":14},{"city_id":4,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":14},{"city_id":1,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":32},{"city_id":1,"start_at":"2023-02-02 17:00:00.000000","date_start":"2023-02-02","headquarter_id":32},{"city_id":1,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":32},{"city_id":1,"start_at":"2023-02-03 17:00:00.000000","date_start":"2023-02-03","headquarter_id":32},{"city_id":1,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":32},{"city_id":1,"start_at":"2023-02-04 17:00:00.000000","date_start":"2023-02-04","headquarter_id":32},{"city_id":1,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":32},{"city_id":1,"start_at":"2023-02-05 17:00:00.000000","date_start":"2023-02-05","headquarter_id":32},{"city_id":1,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":32},{"city_id":1,"start_at":"2023-02-06 17:00:00.000000","date_start":"2023-02-06","headquarter_id":32},{"city_id":1,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":32},{"city_id":1,"start_at":"2023-02-07 17:00:00.000000","date_start":"2023-02-07","headquarter_id":32},{"city_id":1,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":32},{"city_id":1,"start_at":"2023-02-08 17:00:00.000000","date_start":"2023-02-08","headquarter_id":32},{"city_id":19,"start_at":"2023-02-02 16:30:00.000000","date_start":"2023-02-02","headquarter_id":33},{"city_id":19,"start_at":"2023-02-03 16:30:00.000000","date_start":"2023-02-03","headquarter_id":33},{"city_id":19,"start_at":"2023-02-04 16:30:00.000000","date_start":"2023-02-04","headquarter_id":33},{"city_id":19,"start_at":"2023-02-05 16:30:00.000000","date_start":"2023-02-05","headquarter_id":33},{"city_id":19,"start_at":"2023-02-06 16:30:00.000000","date_start":"2023-02-06","headquarter_id":33},{"city_id":19,"start_at":"2023-02-07 16:30:00.000000","date_start":"2023-02-07","headquarter_id":33},{"city_id":19,"start_at":"2023-02-08 16:30:00.000000","date_start":"2023-02-08","headquarter_id":33},{"city_id":1,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":23},{"city_id":1,"start_at":"2023-02-02 17:00:00.000000","date_start":"2023-02-02","headquarter_id":23},{"city_id":1,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":23},{"city_id":1,"start_at":"2023-02-03 17:00:00.000000","date_start":"2023-02-03","headquarter_id":23},{"city_id":1,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":23},{"city_id":1,"start_at":"2023-02-04 17:00:00.000000","date_start":"2023-02-04","headquarter_id":23},{"city_id":1,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":23},{"city_id":1,"start_at":"2023-02-05 17:00:00.000000","date_start":"2023-02-05","headquarter_id":23},{"city_id":1,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":23},{"city_id":1,"start_at":"2023-02-06 17:00:00.000000","date_start":"2023-02-06","headquarter_id":23},{"city_id":1,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":23},{"city_id":1,"start_at":"2023-02-07 17:00:00.000000","date_start":"2023-02-07","headquarter_id":23},{"city_id":1,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":23},{"city_id":1,"start_at":"2023-02-08 17:00:00.000000","date_start":"2023-02-08","headquarter_id":23},{"city_id":5,"start_at":"2023-02-02 15:15:00.000000","date_start":"2023-02-02","headquarter_id":18},{"city_id":5,"start_at":"2023-02-02 17:10:00.000000","date_start":"2023-02-02","headquarter_id":18},{"city_id":5,"start_at":"2023-02-03 15:15:00.000000","date_start":"2023-02-03","headquarter_id":18},{"city_id":5,"start_at":"2023-02-03 17:10:00.000000","date_start":"2023-02-03","headquarter_id":18},{"city_id":5,"start_at":"2023-02-04 15:15:00.000000","date_start":"2023-02-04","headquarter_id":18},{"city_id":5,"start_at":"2023-02-04 17:10:00.000000","date_start":"2023-02-04","headquarter_id":18},{"city_id":5,"start_at":"2023-02-05 15:15:00.000000","date_start":"2023-02-05","headquarter_id":18},{"city_id":5,"start_at":"2023-02-05 17:10:00.000000","date_start":"2023-02-05","headquarter_id":18},{"city_id":5,"start_at":"2023-02-06 15:15:00.000000","date_start":"2023-02-06","headquarter_id":18},{"city_id":5,"start_at":"2023-02-06 17:10:00.000000","date_start":"2023-02-06","headquarter_id":18},{"city_id":5,"start_at":"2023-02-07 15:15:00.000000","date_start":"2023-02-07","headquarter_id":18},{"city_id":5,"start_at":"2023-02-07 17:10:00.000000","date_start":"2023-02-07","headquarter_id":18},{"city_id":5,"start_at":"2023-02-08 15:15:00.000000","date_start":"2023-02-08","headquarter_id":18},{"city_id":5,"start_at":"2023-02-08 17:10:00.000000","date_start":"2023-02-08","headquarter_id":18},{"city_id":8,"start_at":"2023-02-02 15:45:00.000000","date_start":"2023-02-02","headquarter_id":19},{"city_id":8,"start_at":"2023-02-02 17:45:00.000000","date_start":"2023-02-02","headquarter_id":19},{"city_id":8,"start_at":"2023-02-03 15:45:00.000000","date_start":"2023-02-03","headquarter_id":19},{"city_id":8,"start_at":"2023-02-03 17:45:00.000000","date_start":"2023-02-03","headquarter_id":19},{"city_id":8,"start_at":"2023-02-04 15:45:00.000000","date_start":"2023-02-04","headquarter_id":19},{"city_id":8,"start_at":"2023-02-04 17:45:00.000000","date_start":"2023-02-04","headquarter_id":19},{"city_id":8,"start_at":"2023-02-05 15:45:00.000000","date_start":"2023-02-05","headquarter_id":19},{"city_id":8,"start_at":"2023-02-05 17:45:00.000000","date_start":"2023-02-05","headquarter_id":19},{"city_id":8,"start_at":"2023-02-06 15:45:00.000000","date_start":"2023-02-06","headquarter_id":19},{"city_id":8,"start_at":"2023-02-06 17:45:00.000000","date_start":"2023-02-06","headquarter_id":19},{"city_id":8,"start_at":"2023-02-07 15:45:00.000000","date_start":"2023-02-07","headquarter_id":19},{"city_id":8,"start_at":"2023-02-07 17:45:00.000000","date_start":"2023-02-07","headquarter_id":19},{"city_id":8,"start_at":"2023-02-08 15:45:00.000000","date_start":"2023-02-08","headquarter_id":19},{"city_id":8,"start_at":"2023-02-08 17:45:00.000000","date_start":"2023-02-08","headquarter_id":19},{"city_id":1,"start_at":"2023-02-02 15:30:00.000000","date_start":"2023-02-02","headquarter_id":7},{"city_id":1,"start_at":"2023-02-02 17:20:00.000000","date_start":"2023-02-02","headquarter_id":7},{"city_id":1,"start_at":"2023-02-03 15:30:00.000000","date_start":"2023-02-03","headquarter_id":7},{"city_id":1,"start_at":"2023-02-03 17:20:00.000000","date_start":"2023-02-03","headquarter_id":7},{"city_id":1,"start_at":"2023-02-04 15:30:00.000000","date_start":"2023-02-04","headquarter_id":7},{"city_id":1,"start_at":"2023-02-04 17:20:00.000000","date_start":"2023-02-04","headquarter_id":7},{"city_id":1,"start_at":"2023-02-05 15:30:00.000000","date_start":"2023-02-05","headquarter_id":7},{"city_id":1,"start_at":"2023-02-05 17:20:00.000000","date_start":"2023-02-05","headquarter_id":7},{"city_id":1,"start_at":"2023-02-06 15:30:00.000000","date_start":"2023-02-06","headquarter_id":7},{"city_id":1,"start_at":"2023-02-06 17:20:00.000000","date_start":"2023-02-06","headquarter_id":7},{"city_id":1,"start_at":"2023-02-07 15:30:00.000000","date_start":"2023-02-07","headquarter_id":7},{"city_id":1,"start_at":"2023-02-07 17:20:00.000000","date_start":"2023-02-07","headquarter_id":7},{"city_id":1,"start_at":"2023-02-08 15:30:00.000000","date_start":"2023-02-08","headquarter_id":7},{"city_id":1,"start_at":"2023-02-08 17:20:00.000000","date_start":"2023-02-08","headquarter_id":7}],"url_trailer":"https://www.youtube.com/watch?v=pK4IlllODkQ","country_name":"Italia","premier_date":"2023-02-02","movie_gender_id":12,"movie_gender_name":"Aventura","type_of_censorship":"Todo espectador","duration_in_minutes":94}';
        $movie = new MongoPelis();
        $data =  json_decode($data, true);

        $movie->id = $data['id'];
        $movie->code =  $data['code'];
        $movie->is_3d =  $data['is_3d'];
        $movie->name =  $data['name'];
        $movie->image_path =  $data['image_path'];
        $movie->url_trailer =  $data['url_trailer'];
        $movie->summary =  $data['summary'];
        $movie->duration_in_minutes =  $data['duration_in_minutes'];
        $movie->type_of_censorship =  $data['type_of_censorship'];
        $movie->premier_date =  $data['premier_date'];
        $movie->movie_gender_id =  $data['movie_gender_id'];
        $movie->movie_gender_name =  $data['movie_gender_name'];
        $movie->country_id =  $data['country_id'];
        $movie->country_name =  $data['country_name'];
        $movie->movie_times =  $data['movie_times'];
        $movie->trade_name =  'CINESTAR';
        $movie->save();
    }
}
