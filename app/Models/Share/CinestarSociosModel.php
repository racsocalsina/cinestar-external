<?php

namespace App\Models\Share;

use Illuminate\Database\Eloquent\Model;

abstract class CinestarSociosModel  extends  Model
{
    protected $connection = 'cinestar_socios';
    public $timestamps = false;
    public $incrementing = false;

}
