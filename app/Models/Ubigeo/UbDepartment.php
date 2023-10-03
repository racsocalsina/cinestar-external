<?php


namespace App\Models\Ubigeo;


use Illuminate\Database\Eloquent\Model;

class UbDepartment extends Model
{
    protected $table = 'ubdepartments';
    protected $keyType = 'string';

    public function provinces()
    {
        return $this->hasMany(UbProvince::class, 'department_id', 'id')
            ->orderBy('name');
    }
}
