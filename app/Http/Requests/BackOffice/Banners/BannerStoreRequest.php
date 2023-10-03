<?php

namespace App\Http\Requests\BackOffice\Banners;

use App\Enums\BannerType;
use App\Enums\PageWeb;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BannerStoreRequest extends FormRequest
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
                        'required',
                        'string',
                        'max:10',
                        Rule::in(BannerType::ALL_VALUES)
                    ],
            'link' => 'sometimes|string|max:100',
            'trade_name' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'download_app' => 'nullable',
            'page' => 'sometimes|in:'.PageWeb::string()
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'tipo',
            'link' => 'link',
            'image' => 'imagen',
            'trade_name' => 'proyecto'
        ];
    }

    public function messages()
    {
        return [
            'type.required'  => 'Ingrese tipo de banner',
            'type.string'    => 'Tipo de banner incorrecto. Inténtelo nuevamente',
            'type.in'        => 'Tipo de banner incorrecto. Inténtelo nuevamente',
            'link.required'  => 'Ingrese link',
            'link.string'    => 'Link incorrecto. Inténtelo nuevamente',
            'image.nullable' => 'Ingrese imagen',
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
