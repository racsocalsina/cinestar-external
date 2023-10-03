<?php

namespace App\Models\PromotionCorporative;

use App\Models\Share\CinestarSociosModel;
use App\Traits\ModelCinestarSocios;
use App\Traits\ModelIdByPrimaryKey;

class PromotionCorporative extends CinestarSociosModel
{
    use ModelIdByPrimaryKey, ModelCinestarSocios;

    protected $table = 'qmaecod';
    protected $dates = ['fecha_creacion', 'fecha_modificacion'];
    protected $guarded = [];
    protected $primaryKey = null;
    public $incrementing = false;
}
