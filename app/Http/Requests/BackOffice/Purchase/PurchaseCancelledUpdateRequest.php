<?php


namespace App\Http\Requests\BackOffice\Purchase;


use App\Enums\BusinessName;
use App\Enums\PurchaseStatus;
use App\Enums\TradeName;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;
use App\SearchableRules\MovieTimeSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use App\Traits\Requests\HeadquarterImageRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PurchaseCancelledUpdateRequest extends FormRequest
{
    use ApiResponser;

    public function __construct()
    {
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
        ];

        return $rules;
    }

    public function withValidator($validator)
    {

        if (!$validator->fails()) {
            $this->checkAfterValidations();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkAfterValidations()
    {
        $isValid = $this->route('purchase')->status == PurchaseStatus::COMPLETED;

        if(!$isValid)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.purchases.not_valid_for_cancelling')], 422)
            );
    }
}