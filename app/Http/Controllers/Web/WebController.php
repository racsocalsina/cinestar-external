<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PromotionCorporative\PromotionCorporative;
use App\Traits\ApiResponser;
use App\Traits\ChangeDate;

class WebController extends Controller
{
    use ApiResponser, ChangeDate;

    public function __construct()
    {
        if(!env('APP_DEBUG') || env('APP_ENV') == 'production')
            return abort(403);
    }

    public function codes()
    {
        $codes = PromotionCorporative::where('estado', 0)->get();

        return $codes->pluck('codigo');
    }
}
