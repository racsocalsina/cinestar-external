<?php


namespace App\Http\Requests\BackOffice\ProductPrice;

use Illuminate\Foundation\Http\FormRequest;

class ProductPriceSyncRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action' => 'required|string',
            'url'    => 'required|url',
        ];
    }
}
