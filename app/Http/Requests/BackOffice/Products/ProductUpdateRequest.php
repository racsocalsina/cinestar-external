<?php


namespace App\Http\Requests\BackOffice\Products;


use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'image_r' => 'nullable',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'image2_r' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'image.max'      => 'Imagen excedio el tamaño limite de :max kilobytes',
            'image2.max'      => 'Imagen excedio el tamaño limite de :max kilobytes'
        ];
    }
}
