<?php

namespace App\Http\Requests\Auth;

use App\Enums\GlobalEnum;
use App\Helpers\Helper;
use App\Rules\DocumentForType;
use App\Rules\Password;
use App\Traits\ApiResponser;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterClientRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'                 => [
                'required', 'string',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('email', $this->email)
                        ->where('trade_name', Helper::getTradeNameHeader());
                })
            ],
            'name'                  => 'required|string|max:40',
            'lastname'              => 'required|string|max:40',
            'document_type'         => [
                'required',
                'string',
                'max:2',
                Rule::in(GlobalEnum::TYPES_DOCUMENTS)
            ],
            'document_number'       => [
                'required',
                'string',
                'max:12',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('document_number', $this->document_number)
                        ->where('trade_name', Helper::getTradeNameHeader());
                }),
                new DocumentForType($this->document_type)
            ],
            'cellphone'             => [
                'required', 'string', 'size:9',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('cellphone', $this->cellphone)
                        ->where('trade_name', Helper::getTradeNameHeader());
                }),
            ],
            'birthdate'             => 'required|string|date_format:Y-m-d',
            'password'              => [
                'required',
                'string',
                'min:6',
                new Password,
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:6',
                new Password
            ],
            'department_id' => 'required|exists:ubdepartments,id'
        ];
    }

    public function attributes()
    {
        return [
            'document_number'       => 'Número de Documento',
            'password'              => 'Contraseña',
            'password_confirmation' => 'Contraseña',
        ];
    }

    public function messages()
    {
        return [
            'email.required'                 => 'Falta ingresar el correo',
            'email.unique'                   => 'El correo ya se encuentra registrado',
            'name.required'                  => 'Falta ingresar el nombre',
            'lastname.required'              => 'Falta ingresar el apellido',
            'document_type.required'         => 'Falta ingresar el tipo de documento',
            'document_type.in'               => 'El tipo de documento seleccionado no es valido',
            'document_number.required'       => 'Falta ingresar el número de documento',
            'document_number.unique'         => 'El número de documento ingresado ya se encuentra registrado',
            'cellphone.required'             => 'Falta ingresar el número de telefono',
            'cellphone.size'                 => 'El número de telefono ingresado es incorrecto',
            'cellphone.unique'               => 'El número de telefono ingresado ya se encuentra registrado',
            'birthdate.required'             => 'Falta ingresar la fecha de nacimiento',
            'birthdate.date_format'          => 'La fecha de nacimiento es incorrecta',
            'password.required'              => 'Falta ingresar la contraseña',
            'password.min'                   => 'La contraseña debe tener minimo 6 caracteres',
            'password.confirmed'             => 'Las contraseñas ingresadas no son iguales',
            'password_confirmation.required' => 'Falta ingresar la contraseña',
            'password_confirmation.min'      => 'La contraseña debe tener minimo 6 caracteres',
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
