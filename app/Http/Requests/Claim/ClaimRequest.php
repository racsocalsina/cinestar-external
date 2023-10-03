<?php


namespace App\Http\Requests\Claim;


use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;

class ClaimRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sede_district_id'       => 'required|string',
            'claim_type_id'          => 'required|integer',
            'identification_type_id' => 'required|integer',
            'detail'                 => 'required|string',
            'amount'                 => 'required',
            'name'                   => 'required|string',
            'lastname'               => 'required|string',
            'older'                  => 'required|integer',
            'document_type_id'       => 'required|integer',
            'document_number'        => 'required|string',
            'representative_name'    => 'required_if:older,=,0',
            'address'                => 'required|string',
            'person_district_id'     => 'required|string',
            'cellphone'              => 'required|integer|digits_between:9,9',
            'email'                  => 'required|string|email',
        ];
    }

    public function messages()
    {
        return [
            'cellphone.digits_between' => Lang::get('validation.claim.cellphone_digits_between'),
            'representative_name.required_if' => Lang::get('validation.claim.representative_name_required_if')
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
