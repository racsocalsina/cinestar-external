<?php

namespace App\Models\Countries;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
}
