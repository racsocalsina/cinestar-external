<?php

namespace App\Models\MovieGenders;

use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\Models\Activatable;
use Illuminate\Database\Eloquent\Model;

class MovieGender extends Model implements ActivatableInterface
{
    use Activatable;

    protected $table = 'movie_genders';
    protected $guarded = ['id'];
    public $timestamps = false;
}
