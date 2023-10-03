<?php


namespace App\Http\Requests\Contact;


use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => 'required|string',
            'lastname'      => 'required|string',
            'email'         => 'required|string|email',
            'district_name' => 'required|string',
            'message'       => 'required|string',
        ];
    }

    public function messages()
    {
        return [
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
