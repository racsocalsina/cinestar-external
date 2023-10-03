<?php

namespace App\Models\Headquarters;

use Jenssegers\Mongodb\Eloquent\Model;

class TestModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title', 'body'
    ];
}