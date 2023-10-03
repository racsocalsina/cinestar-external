<?php


namespace App\Http\Requests\JobApplication;



use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JobApplicationRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'            => 'required|string',
            'lastname'        => 'required|string',
            'email'           => 'required|string|email',
            'address'         => 'required|string',
            'document_number' => 'required|string',
            'district_name'   => 'required|string',
            'birth_date'      => 'required|date|date_format:Y-m-d',
            'education_level' => 'required|string',
            'cv'              => 'required|file|mimes:pdf,doc,docx',
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
