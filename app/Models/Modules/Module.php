<?php


namespace App\Models\Modules;


use App\Models\Permissions\Permission;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

}
