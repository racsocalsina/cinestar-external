<?php


namespace App\Http\Requests\Purchase;


use App\Helpers\FunctionHelper;
use App\Rules\CheckPurchaseBeforePay;
use App\Rules\Purchase\CheckCCSecurityCode;
use App\Rules\Purchase\CheckCCTokenId;
use App\Rules\Purchase\CheckInternalIsAvailable;
use App\Rules\Purchase\CheckPurchaseBeforeUpdate;
use App\Services\PayU\Shared\Enums\PaymentMethods;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\RequiredIf;
use App\Models\Products\Product;

class PurchasePaymentRequest extends FormRequest
{
    use ApiResponser;

    private $purchase;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $authenticated = FunctionHelper::getApiUser() ? true : false;

        return [
            'purchase_id' => [
                'required', 'int', 'exists:purchases,id',
                new CheckPurchaseBeforePay($this->cc_data['token'], $this->cc_data['number']),
                new CheckPurchaseBeforeUpdate($this->purchase_id),
                new CheckInternalIsAvailable($this->purchase_id)
            ],
            //'pickup_date' => 'date',
            'document_type'           => 'required|max:2|exists:document_types,code',
            'document_number'         => 'required|max:20',
            'name'                    => 'required|max:50',
            'lastname'                => 'required|max:50',
            'email'                   => 'required|email|max:50',
            'voucher_type'            => 'required|in:01,03',
            'ruc'                     => 'required_if:voucher_type,==,01|max:11',
            'business_name'           => 'required_if:voucher_type,==,01|max:50',
            'address'                 => 'nullable|max:200',
            'phone'                   => 'nullable|max:20',
            'device_session_id'       => 'nullable|string',
            'cc_data.token'           => [
                new RequiredIf($authenticated),
                new CheckCCTokenId($this->purchase_id),
                'nullable'
            ],
            'cc_data.security_code'   => [
                'required',
                new CheckCCSecurityCode($this->purchase_id)
            ],
            'cc_data.number'          => [
                new RequiredIf(!$authenticated),
                'nullable',
                'numeric'
            ],
            'cc_data.payment_method'  => [
                new RequiredIf(!$authenticated),
                'nullable',
                'in:' . implode(',', PaymentMethods::ALL_VALUES)
            ],
            'cc_data.expiration_date' => [
                new RequiredIf(!$authenticated),
                'nullable',
                'date_format:y/m'
            ],
            'cc_data.full_name'       => [
                new RequiredIf(!$authenticated),
                'nullable'
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
