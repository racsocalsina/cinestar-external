<?php


namespace App\Http\Requests\Auth;


use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordApp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'document_number' => 'número de documento',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document_number' => 'required',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required'
        ];
    }
}
