<?php


namespace App\Services\Reports\QrStatus\Actions;


use App\Helpers\Helper;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Traits\ApiResponser;
use GuzzleHttp\Client;
use Illuminate\Http\Exceptions\HttpResponseException;

class QrStatusReport
{
    use ApiResponser;

    private $code;

    public function execute($code)
    {
        $this->code = $code;
        return $this->getInvoiceDataFromInternal();
    }

    public function getInvoiceDataFromInternal(){

        $purchaseVoucher = PurchaseVoucher::with(['purchase.headquarter'])
            ->whereRaw("concat(internal_serial_number, '-', document_number) = '" . $this->code . "'")
            ->first();

        return $this->callInternal($purchaseVoucher->purchase);
    }

    private function callInternal($purchase)
    {
        $token = Helper::loginInternal($purchase->headquarter);
        $client = new Client();
        $api_url = Helper::addSlashToUrl($purchase->headquarter->api_url);
        $api_url = "{$api_url}api/v1/consumer/reports/qr-status";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $response = $client->get($api_url, [
            'headers' => $headers,
            'query' => [
                'code' => $this->code
            ]
        ]);
        $body = (string) $response->getBody();
        $response = json_decode($body, true);

        if($response['data'] == null)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => 'No hay datos de la compra previamente enviados desde external'], 422)
            );

        return $response;
    }

}