<?php


namespace App\Models\HeadquarterImages;


use App\Enums\GlobalEnum;
use App\Models\Headquarters\Headquarter;
use Illuminate\Database\Eloquent\Model;

class HeadquarterImage extends Model
{
    protected $fillable = ['path', 'is_main_image'];

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function getPathAttribute()
    {
        return config('constants.path_images').env('BUCKET_ENV').GlobalEnum::HEADQUARTERS_FOLDER. "/" . $this->attributes['path'];
    }
}
