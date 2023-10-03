<?php

namespace App\Models\UsersPartners;

use App\Models\Share\CinestarSociosModel;
use App\Traits\ModelCinestarSocios;
use App\Traits\ModelIdByPrimaryKey;

class UserPartner extends CinestarSociosModel
{
    use ModelIdByPrimaryKey, ModelCinestarSocios;
    protected $keyType = 'string';
    protected $table = 'qmaesoc';
    protected $primaryKey ='soccod';
    protected $appends = ['id_company'];

    protected $guarded = [];

    public function getIdCompanyAttribute(){
        return substr($this->attributes['soccod'], 0, 1);
    }

    public function getFullNameAttribute(){
        return "{$this->socnom} {$this->socno2}";
    }

    public function getTicketPointsAttribute(){
        return $this->attributes['socpun'];
    }

    public function getChocoPointsAttribute(){
        return $this->attributes['so2pun'];
    }

    public function getTicketHistoryPointsAttribute(){
        return $this->attributes['socacu'];
    }

    public function getChocoHistoryPointsAttribute(){
        return $this->attributes['so2acu'];
    }
}
