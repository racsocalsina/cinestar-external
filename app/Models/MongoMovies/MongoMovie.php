<?php

namespace App\Models\MongoMovies;

use Jenssegers\Mongodb\Eloquent\Model;


class MongoMovie extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'movies';
    protected $hidden = ['_id'];
    protected $dates = ['created_at', 'updated_at', 'datetime'];
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
        'status',
        'movie_gender_id',
        'movie_gender_name',
        'country_id',
        'country_name',
        'movie_times',
    ];
}
