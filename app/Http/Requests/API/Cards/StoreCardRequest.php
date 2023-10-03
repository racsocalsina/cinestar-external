<?php


namespace App\Http\Requests\API\Cards;


use App\Services\PayU\Shared\Enums\PaymentMethods;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCardRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => 'required|in:' . implode(',', PaymentMethods::ALL_VALUES),
            'number'          => 'required|numeric',
            'expiration_date' => 'required|string|date_format:y/m',
            'full_name'      => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
