<?php


namespace App\Helpers;


use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Auth;

class EloquentHelper
{
    public static function addHeadquarterFilterByAdminRole($column, &$queryOrParam, $type = 'query')
    {
        $admin = Auth::user();

        if($admin->hasRole(RoleEnum::SUPER_ADMIN) || $admin->hasRole(RoleEnum::MARKETING))
            return;

        // set headquarter by param
        if($type == 'param')
        {
            $queryOrParam[$column] = $admin->headquarter_id;
            return;
        }

        // set headquarter by "query"
        $queryOrParam->where($column, $admin->headquarter_id);
        return;
    }
}
