<?php

namespace App\Rules;

use App\Models\Products\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckProductCodeExist implements Rule
{
    public function passes($attribute, $value)
    {
        $data = Product::where('code', $value)->first();

        if(isset($data->id)){
            return true;
        }
        return false;
    }

    public function message()
    {
        return 'El producto no existe.';
    }
}
