<?php


namespace App\Http\Requests\Purchase;


use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Rules\Purchase\CheckInternalIsAvailable;
use App\Rules\Purchase\CheckPurchaseBeforeUpdate;
use App\Rules\Purchase\PurchaseUpdateSweetItems;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSweetPurchaseRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'headquarter_id'      => [
                'required',
                'int',
                'exists:headquarters,id',
                new CheckPurchaseBeforeUpdate($this->purchase->id),
                new CheckInternalIsAvailable(null, null, $this->headquarter_id)
            ],
            'sweets' => [
                'nullable',
                'array',
                new PurchaseUpdateSweetItems($this->purchase->id, $this->headquarter_id)
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

    public function withValidator($validator)
    {

    }
}
