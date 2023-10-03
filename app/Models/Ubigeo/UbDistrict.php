<?php


namespace App\Models\Ubigeo;


use Illuminate\Database\Eloquent\Model;

class UbDistrict extends Model
{
    protected $table = 'ubdistricts';
    protected $keyType = 'string';

    public function province()
    {
        return $this->hasOne(UbProvince::class, 'id', 'province_id');
    }

    public function department()
    {
        return $this->hasOne(UbDepartment::class, 'id', 'department_id');
    }


}
