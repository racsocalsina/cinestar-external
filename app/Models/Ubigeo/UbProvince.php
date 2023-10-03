<?php


namespace App\Models\Ubigeo;


use Illuminate\Database\Eloquent\Model;

class UbProvince extends Model
{
    protected $table = 'ubprovinces';
    protected $keyType = 'string';

    public function districts()
    {
        return $this->hasMany(UbDistrict::class, 'province_id', 'id')
            ->orderBy('name');
    }
}
