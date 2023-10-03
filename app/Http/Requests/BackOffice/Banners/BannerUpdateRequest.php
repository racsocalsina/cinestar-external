<?php

namespace App\Http\Requests\BackOffice\Banners;

use App\Enums\BannerType;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BannerUpdateRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => [
                        'string',
                        'max:10',
                        Rule::in(BannerType::ALL_VALUES)
                    ],
            'link' => 'sometimes|max:100',
            'trade_name' => 'string|max:20',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:3000',
            'download_app' => 'nullable'
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'Type',
            'link' => 'Link',
            'image' => 'Imagen',
            'trade_name' => 'Proyecto'
        ];
    }

    public function messages()
    {
        return [
            'type.string'    => 'Tipo de banner incorrecto. Inténtelo nuevamente',
            'type.in'        => 'Tipo de banner incorrecto. Inténtelo nuevamente',
            'link.string'    => 'Link incorrecto. Inténtelo nuevamente',
            'image.mimes'    => 'Imagen incorrecto. Inténtelo nuevamente',
            'image.max'      => 'Imagen excedio el tamaño limite de :max kilobytes',
        ];
    }

    public function withValidator($validator)
    {
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
