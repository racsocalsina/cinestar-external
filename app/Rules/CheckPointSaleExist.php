<?php

namespace App\Rules;

use App\Models\Headquarters\Headquarter;
use Illuminate\Contracts\Validation\Rule;

class CheckPointSaleExist implements Rule
{

    public function passes($attribute, $value)
    {
        $pointSale = substr($value, 1, 1);

        if(isset($pointSale)){
            $data = Headquarter::where('point_sale', $pointSale)->first();
            if(isset($data->id)){
                return true;
            }
        }
        return false;
    }

    public function message()
    {
        return 'El punto de venta no existe.';
    }
}
