<?php


namespace App\Http\Requests\BackOffice\Reports;


use App\Enums\TradeName;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExhibitorMonthlyRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date'       => 'required|string|date_format:Y-m',
            'trade_name' => 'required|in:' . implode(',', TradeName::ALL_VALUES),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}