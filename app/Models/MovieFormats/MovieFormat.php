<?php

namespace App\Models\MovieFormats;

use App\Models\Headquarters\Headquarter;
use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\Models\Activatable;
use Illuminate\Database\Eloquent\Model;

class MovieFormat extends Model implements ActivatableInterface
{
    use Activatable;

    protected $table = 'movie_formats';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];

    public function headquarters()
    {
        return $this->belongsToMany(Headquarter::class, 'headquarter_movie_formats', 'movie_format_id');
    }
}
