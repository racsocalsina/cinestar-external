<?php


namespace App\Traits;


trait ModelIdByPrimaryKey
{

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }
}
