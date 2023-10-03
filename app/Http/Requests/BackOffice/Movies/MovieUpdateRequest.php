<?php


namespace App\Http\Requests\BackOffice\Movies;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description'  => 'string',
            'gender_id'    => 'nullable|integer|exists:movie_genders,id',
            'country_id'   => 'nullable|integer|exists:countries,id',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'url_trailer'  => 'nullable|url',
            'premier_date' => 'nullable|date|date_format:Y-m-d',
            'status'       => 'nullable|bool',
        ];
    }

    public function messages()
    {
        return[
            'image.max' => 'La :attribute ha excedido el tamaño máximo de :max kilobytes'
        ];
    }
}
