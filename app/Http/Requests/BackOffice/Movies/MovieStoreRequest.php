<?php


namespace App\Http\Requests\BackOffice\Movies;


use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action' => 'required',
            'url' => 'required',
        ];
    }
}
