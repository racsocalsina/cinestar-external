<?php


namespace App\Models\Purchases\Repositories;


use App\Enums\GlobalEnum;
use App\Enums\PointHistoryTypes;
use App\Enums\SalesType;
use App\Enums\VoucherType;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Http\Resources\BackOffice\Purchases\PurchasePaymentDataQR;
use App\Models\PointsHistory\PointHistory;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseShippedInternalLog\PurchaseShippedInternalLog;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\SweetsSold\SweetSold;
use App\Models\Tickets\Ticket;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class PurchaseInternalRepository implements PurchaseInternalRepositoryInterface
{
    private $salesType;
    private $purchaseVoucher;
    private $purchaseTypeData;
    private $purchase;
    private $movieTime;
    private $headquarter;
    private $config;
    private $customer;
    private $pointHistory;

    public function sendPurchaseDataToInternal(PurchaseVoucher $purchaseVoucher)
    {
        $this->setData($purchaseVoucher);
        $token = Helper::loginInternal($this->headquarter);
        $api_url = Helper::addSlashToUrl($this->headquarter->api_url);
        $client = new Client();
        $URL_SEND_PURCHASE = $api_url . ($this->salesType == SalesType::TICKET ? "api/v1/consumer/purchases" : "api/v1/consumer/purchase-sweets");
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $chairs = [];
        $items = [];
        $movkey = $this->purchaseVoucher->internal_serial_number . '-' . $this->purchaseVoucher->document_number;

        if ($this->salesType == SalesType::TICKET) {

            $tickets = Ticket::with(['purchaseItem', 'movie_time_tariff.movie_tariff'])
                ->where('purchase_id', $this->purchaseVoucher->purchase_id)
                ->get();

            foreach ($tickets as $item) {
                array_push($chairs, [
                    'row'    => $item->chair_row,
                    'column' => $item->chair_column,
                    'tariff' => $item->movie_time_tariff->movie_tariff->remote_funtar,
                    'price'  => $item->purchaseItem->paid_amount,
                ]);
            }

        } else {
            $items = $this->getSweetItems();
        }

        $facigv = $this->config['facigv'];
        $facmun = $this->config['facmun'];

        if (!isset($facigv) || FunctionHelper::IsNullOrEmptyString($facigv))
            $facigv = 0;

        if (!isset($facmun) || FunctionHelper::IsNullOrEmptyString($facmun))
            $facmun = 0;

        $movva1 = 0;
        $movva2 = 0;
        $movval = 0;
        $movmun = 0;
        $movigv = 0;

        if ($this->purchaseTypeData->total > 0) {
            if ($this->salesType == SalesType::TICKET) {
                $movva1 = $this->purchaseTypeData->total / (1 + (floatval($facigv) / 100)) / (1 + (floatval($facmun) / 100));
                $movva1 = round($movva1, 2);
                $movmun = round($movva1 * (floatval($facmun) / 100), 2);
            } else {
                $movva1 = $this->purchaseTypeData->total / (1 + (floatval($facigv) / 100));
                $movva1 = round($movva1, 2);
                $movmun = 0;
            }

            $movval = $movva1;
            $movigv = round($this->purchaseTypeData->total - ($movval + $movmun), 2);
        }
        $purchaseVoucher->refresh();
        $params['json'] = [
            'purchase_id'         => $this->purchaseVoucher->purchase_id,
            'sales_point_key'     => $this->headquarter->point_sale,
            'currency_key'        => 'S',
            'movva1'              => $movva1,
            'movva2'              => $movva2,
            'movval'              => $movval,
            'tax_igv'             => $movigv,
            'tax_city'            => $movmun,
            'percentage_tax_igv'  => $facigv,
            'percentage_tax_city' => $facmun,
            'sales_type'          => $this->salesType,
            'total'               => $this->purchaseTypeData->total,
            'total_payment'       => $this->purchaseTypeData->total,
            'emit_date_at'        => $this->purchase->created_at->format('Y-m-d H:i:s'),
            'hash'                => $this->purchaseVoucher->hash,
            'serial_number'       => $purchaseVoucher->internal_serial_number,
            'document_number'     => $purchaseVoucher->document_number,
            'document_type'       => $this->purchaseTypeData->purchase->voucher_type === VoucherType::CODE_TICKET ? VoucherType::NAME_TICKET : VoucherType::NAME_INVOICE,
            'points'              => $this->purchaseTypeData->points,
            'expiration_date'     => $this->pointHistory ? $this->pointHistory->expiration_date : null,
            'customer'            => [
                'name'            => $this->purchaseTypeData->purchase->voucher_type === VoucherType::CODE_TICKET ?
                    $this->purchase->payment_gateway_info->name . ' ' . $this->purchase->payment_gateway_info->lastname :
                    $this->purchase->payment_gateway_info->business_name,
                'email'           => $this->purchase->payment_gateway_info->email,
                'cinema_chain'    => ($this->headquarter->trade_name == 'CINESTAR' ? 1 : 2),
                'ruc'             => $this->purchase->payment_gateway_info->ruc,
                'document_number' => $this->purchase->payment_gateway_info->document_number,
                'partner_code'    => $this->customer ? $this->customer->user_partner->soccod : null,
                'partner_name'    => $this->customer ? substr($this->customer->user_partner->full_name, 0, 40) : null,
            ],
            'movie_time'          => [
                'nro_room'    => $this->movieTime ? $this->movieTime->room->room_number : null,
                'fun_key'     => $this->movieTime ? $this->movieTime->remote_funkey : null,
                'fun_nro'     => $this->movieTime ? $this->movieTime->fun_nro : null,
                'fun_pel'     => $this->purchase->movie ? $this->purchase->movie->code : null,
                'fun_date'    => $this->movieTime ? Carbon::createFromFormat('Y-m-d H:i:s', $this->movieTime->full_start_at)->format('Y-m-d H:i') : null,
                'is_presale'  => $this->movieTime ? $this->movieTime->is_presale : null,
                'is_numerate' => $this->movieTime ? $this->movieTime->is_numerated : null,
            ],
            'chairs'              => $chairs,
            'items'               => $items,
            'ejson'               => $this->getJsonDataForQR($movkey,$this->headquarter->id)
        ];

        $params['headers'] = $headers;
        $this->savePurchaseShippedInternal($purchaseVoucher->purchase_id,  $params['json']);
        $options = ['timeout' => 60];
        try {
            $client->post($URL_SEND_PURCHASE, $params, $options);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = $e->getResponse()->getBody();
                if ($statusCode >= 400 && $statusCode < 500) {
                    throw new \Exception("Error de cliente: {$statusCode} - {$body}", $e->getCode(), $e);
                } elseif ($statusCode >= 500) {
                    throw new \Exception("Error de servidor: {$statusCode} - {$body}", $e->getCode(), $e);
                } else {
                    throw new \Exception("Otro tipo de error: {$e->getMessage()}", $e->getCode(), $e);
                }
            } else {
                throw new \Exception("Error de conexión: {$e->getMessage()}", $e->getCode(), $e);
            }
        }
        $this->purchaseTypeData->send_internal = GlobalEnum::COMPLETED_STATUS;
        $this->purchaseTypeData->save();
    }

    private function savePurchaseShippedInternal($purchase_id, $data){
        PurchaseShippedInternalLog::create([
            'purchase_id' => $purchase_id,
            'request' => json_encode($data)
        ]);
    }

    private function setData($purchaseVoucher): void
    {
        $settingRepository = \App::make(SettingRepositoryInterface::class);

        $this->salesType = $purchaseVoucher->purchase_sweet_id == null ? SalesType::TICKET : SalesType::SWEET;
        $this->purchaseVoucher = $purchaseVoucher;

        if ($this->salesType == SalesType::TICKET) {
            $this->purchaseTypeData = $this->purchaseVoucher->purchase_ticket;
        } else {
            $this->purchaseTypeData = $this->purchaseVoucher->purchase_sweet;
        }

        $this->purchase = $this->purchaseTypeData->purchase->loadMissing([
            'headquarter', 'payment_gateway_info', 'purchase_items', 'movie_time.room', 'movie',
            'user.customer.user_partner'
        ]);

        $this->movieTime = $this->purchase->movie_time;
        $this->headquarter = $this->purchase->headquarter;
        $this->config = $settingRepository->getCommunitySystemVars($this->headquarter->id)->config;

        if ($this->purchase->user)
            $this->customer = $this->purchase->user->customer;

        $this->pointHistory = PointHistory::where('remote_movkey', $this->purchaseTypeData->remote_movkey)
            ->where('type', PointHistoryTypes::GANADO)
            ->first();
    }

    private function getSweetItems()
    {
        $sweets = SweetSold::with(['purchaseItem', 'product', 'headquarter_product', 'purchase.headquarter'])
            ->where('purchase_id', $this->purchase->id)
            ->get();

        $uniqueSweets = $sweets->unique('code');

        $items = [];
        $index = 0;

        foreach ($uniqueSweets as $key => $item) {

            $index += 1;
            $quantity = $sweets->where('code', $item->code)->count();
            $total = $item->price * $quantity;

            array_push($items, [
                'is_canceled'       => '',
                'nro_item'          => $index,
                'date_issue'        => now()->toDateString(),
                'product_code'      => $item->code,
                'product_name'      => $item->name,
                'unit_price'        => $item->headquarter_product->price,
                'sales_unit'        => $item->headquarter_product->sales_unit,
                'product_name_abbr' => $item->product->name_abbr,
                'is_igv'            => $item->headquarter_product->igv,
                'point_sale'        => $item->purchase->headquarter->point_sale,
                'product_name2'     => ($item->code == '999999' ? $item->name : ''),
                'is_coupon'         => '0',
                'coin'              => 'S',
                'original_coin'     => 'S',
                'original_amount'   => $item->headquarter_product->price,
                'total'             => $total,
                'number_units'      => $quantity,
                'promo_code'        => '',
                'amount_promo'      => '',
                'percentage_promo'  => '',
                'number_units2'     => ($item->code == '999999' ? 0 : $quantity),
                'total_dollars'     => '0.00',
                'movcen'            => ''
            ]);
        }

        return $items;
    }


    private function getJsonDataForQR($remoteKey, $headquarter_id): string
    {
        $purchaseRepositoryInterface = \App::make(PurchaseRepositoryInterface::class);
        $data = $purchaseRepositoryInterface->getByRemoteKey($remoteKey, $headquarter_id);

        if (!$data)
            return "";

        return json_encode(new PurchasePaymentDataQR($data));
    }
}
