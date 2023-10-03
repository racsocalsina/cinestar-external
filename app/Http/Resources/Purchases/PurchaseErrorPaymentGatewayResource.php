<?php


namespace App\Http\Resources\Purchases;


use App\Enums\PurchaseStatus;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseErrorPaymentGatewayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->purchase_id,
            'status'   => 'error',
            'error'    => $this->getError(),
            'datetime' => Helper::getDateTimeFormat($this->created_at),
            'total'    => $this->purchase->amount
        ];
    }

    private function getError()
    {
        if ($this->status == PurchaseStatus::ERROR_PAYMENT_GATEWAY) {
            $data = json_decode($this->error);

            if (isset($data->data)) {
                return $data->data->ACTION_DESCRIPTION;
            }

            if (isset($data->errorMessage)) {
                return $data->errorMessage;
            }

            return __('app.purchases.payment_gateway_error');

        } else {
            return $this->error;
        }
    }

}
