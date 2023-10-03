<?php

namespace App\Http\Requests\Auth;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Número de Documento',
            'password' => 'Contraseña',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Ingrese su usuario',
            'username.exists'   => 'Usuario o clave incorrecta. Inténtelo nuevamente',
            'password.required' => 'Ingrese su contraseña',
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
