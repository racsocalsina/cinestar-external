<?php


namespace App\Http\Requests\BackOffice\ChocoPromotions;


use Illuminate\Foundation\Http\FormRequest;

class ChocoPromotionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
        ];
    }

    public function messages()
    {
        return[
            'image.max' => 'La :attribute ha excedido el tamaño máximo de :max kilobytes'
        ];
    }
}

