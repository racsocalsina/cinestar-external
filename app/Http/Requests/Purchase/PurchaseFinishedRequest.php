<?php


namespace App\Http\Requests\Purchase;


use App\Models\Purchases\Purchase;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PurchaseFinishedRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->checkStatus();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkStatus()
    {
        $purchaseId = null;

        try {
            $purchaseId = decrypt($this->code);
        } catch (DecryptException $e) {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.purchases.invalid_code')], 422)
            );
        }

        $purchase = Purchase::find($purchaseId);

        if ($purchase == null) {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.purchases.not_exist')], 422)
            );
        }
    }

}
