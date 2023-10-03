<?php


namespace App\Http\Requests\BackOffice\TicketAwards;


use Illuminate\Foundation\Http\FormRequest;

class TicketAwardUpdateRequest extends FormRequest
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
        return [
            'image.max'      => 'Imagen excedio el tamaño limite de :max kilobytes'
        ];
    }
}
