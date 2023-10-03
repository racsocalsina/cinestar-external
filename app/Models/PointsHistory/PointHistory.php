<?php

namespace App\Models\PointsHistory;

use App\Models\Share\CinestarSociosModel;
use App\Traits\ModelCinestarSocios;

class PointHistory extends CinestarSociosModel
{
    use ModelCinestarSocios;
    protected $table = 'points_history';
    protected $dates = ['created_at', 'expiration_date'];
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function scopeExcludeOldRecords($query)
    {
        $fromDate = (now()->addYear(-1)->format('Y-m-d'));
        return $query->whereDate('created_at', '>=', $fromDate);
    }
}
