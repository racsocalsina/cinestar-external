<?php


namespace App\Http\Requests\BackOffice\Reports;


use App\Enums\GlobalEnum;
use App\Enums\TradeName;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\SearchableRules\MovieTimeSearchableRule;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QrStatusReportRequest extends FormRequest
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
            $this->checkCode();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkCode()
    {
        $purchaseVoucher = PurchaseVoucher::with(['purchase_ticket', 'purchase_sweet'])
            ->whereRaw("concat(internal_serial_number, '-', document_number) = '" . $this->code . "'")
            ->first();

        if (!$purchaseVoucher)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => 'Número de comprobante no existe'], 422)
            );

        if ($purchaseVoucher->purchase_ticket && $purchaseVoucher->purchase_ticket->send_internal != GlobalEnum::COMPLETED_STATUS)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => 'La compra de este número de comprobante todavía no ha sido enviado a la sede'], 422)
            );

        if ($purchaseVoucher->purchase_sweet && $purchaseVoucher->purchase_sweet->send_internal != GlobalEnum::COMPLETED_STATUS)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => 'La compra de este número de comprobante todavía no ha sido enviado a la sede'], 422)
            );
    }
}