<?php


namespace App\Http\Requests\BackOffice\Countries;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required|string|max:30|alpha_spaces'
        ];
    }

}
