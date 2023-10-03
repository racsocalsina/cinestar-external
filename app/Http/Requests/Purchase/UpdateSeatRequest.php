<?php


namespace App\Http\Requests\Purchase;

use App\Rules\CheckSeatIsAvailableByIndex;
use App\Rules\Purchase\CheckPurchaseBeforeUpdate;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSeatRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'index' => [
                'required',
                'integer',
                new CheckPurchaseBeforeUpdate($this->id),
                new CheckSeatIsAvailableByIndex($this->id, $this->index, $this->status)
            ],
            'status' => 'required|boolean'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

}
