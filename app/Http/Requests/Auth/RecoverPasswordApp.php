<?php


namespace App\Http\Requests\Auth;


use Illuminate\Foundation\Http\FormRequest;

class RecoverPasswordApp extends FormRequest
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
            'cellphone' => 'celular',
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
            'cellphone' => 'required|size:9',
        ];
    }
}
