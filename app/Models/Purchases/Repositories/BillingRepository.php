<?php


namespace App\Models\Purchases\Repositories;


use App\Enums\ElectronicBilling;
use App\Enums\GlobalEnum;
use App\Enums\SalesType;
use App\Enums\VoucherType;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\SweetsSold\SweetSold;
use App\Models\Tickets\Ticket;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BillingRepository implements BillingRepositoryInterface
{
    private PurchaseRepositoryInterface $purchaseRepository;
    private SettingRepositoryInterface $settingRepository;

    private $salesType;
    private $purchaseTypeData;
    private $purchaseVoucher;
    private $purchase;
    private $headquarter;
    private $config;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository, SettingRepositoryInterface $settingRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Esta funcion recibe el voucher de la compra para ser procesadas por el facturador una vez que el api de esta responda de forma
     * satisfactoria recien se envia los datos de la compra a la sede (internal) para que esta info sea almacenada en
     * su ERP.
     * @param $purchaseVoucher
     */
    public function callApi($purchaseVoucher)
    {
        $this->setData($purchaseVoucher);
        //$this->generateElectronicDocument();
        $this->updatePurchaseVoucherWithoutCallBillingApi();
    }

    private function setData($purchaseVoucher)
    {
        $this->salesType = $purchaseVoucher->purchase_sweet_id == null ? SalesType::TICKET : SalesType::SWEET;
        $this->purchaseVoucher = $purchaseVoucher;
        $this->purchaseTypeData = $this->getPurchaseTypeData($purchaseVoucher);
        $this->purchase = $this->purchaseTypeData->purchase;
        $this->headquarter = $this->purchaseTypeData->purchase->headquarter;
        $this->config = $this->formatConfig($this->settingRepository->getCommunitySystemVars($this->headquarter->id));
    }

    private function getPurchaseTypeData($purchaseVoucher)
    {
        if ($this->salesType == SalesType::TICKET) {
            $purchaseTypeData = $purchaseVoucher->purchase_ticket;
        } else {
            $purchaseTypeData = $purchaseVoucher->purchase_sweet;
        }

        return $purchaseTypeData->loadMissing([
            'purchase.headquarter', 'purchase.payment_gateway_info', 'purchase.purchase_items'
        ]);
    }

    private function generateElectronicDocument()
    {
        $envFeUrl = strtolower("fe_api_url_{$this->headquarter->business_name}");
        $envFeUrl = FunctionHelper::getValueSystemConfigurationByKey($envFeUrl);
        $envFeUrl = Helper::addSlashToUrl($envFeUrl);

        $envFeToken = strtolower("fe_token_{$this->headquarter->business_name}");
        $envFeToken = FunctionHelper::getValueSystemConfigurationByKey($envFeToken);

        $apiUrlSendTicket = "{$envFeUrl}api/documents";

        $client = new Client([
            'verify' => false
        ]);
        $json = $this->buildDataFE();
        $params['json'] = $json;
        $params['headers'] = [
            'Authorization' => 'Bearer ' . $envFeToken,
            'Accept'        => 'application/json',
        ];

        $response = $client->post($apiUrlSendTicket, $params);
        $body = (string)$response->getBody();
        $body = json_decode($body, true);
        $this->updatePurchaseVoucherWithResponseData($body, $json);

        return $body;
    }

    private function buildDataFE(): Collection
    {
        $itemsCollect = null;

        if ($this->salesType === SalesType::TICKET) {
            $itemsCollect = $this->buildItemsDataForTickets();
        } else {
            $itemsCollect = $this->buildItemsDataForSweets();
        }

        return collect([
            'serie_documento'              => $this->purchaseVoucher->serial_number,
            'numero_documento'             => $this->purchaseVoucher->document_number,
            'fecha_de_emision'             => $this->purchaseVoucher->date_issue->format('Y-m-d'),
            'hora_de_emision'              => $this->purchaseVoucher->date_issue->format('H:i:s'),
            'codigo_tipo_operacion'        => ElectronicBilling::CODE_SALE,
            'codigo_tipo_documento'        => $this->purchase->voucher_type,
            'codigo_tipo_moneda'           => ElectronicBilling::CURRENCY_CODE_SOLES,
            'fecha_de_vencimiento'         => $this->purchaseVoucher->date_issue->format('Y-m-d'),
            'numero_orden_de_compra'       => '',
            'datos_del_cliente_o_receptor' => collect([
                'codigo_tipo_documento_identidad'    => $this->purchase->voucher_type === VoucherType::CODE_TICKET ? ElectronicBilling::DOCUMENT_TYPE_CODE_TICKET : ElectronicBilling::DOCUMENT_TYPE_CODE_INVOICE,
                'numero_documento'                   => $this->purchase->voucher_type === VoucherType::CODE_TICKET
                    ? $this->purchase->payment_gateway_info->document_number
                    : $this->purchase->payment_gateway_info->ruc,
                'apellidos_y_nombres_o_razon_social' => $this->purchase->voucher_type === VoucherType::CODE_TICKET
                    ? $this->purchase->payment_gateway_info->name . ' ' . $this->purchase->payment_gateway_info->lastname
                    : $this->purchase->payment_gateway_info->business_name,
                'codigo_pais'                        => ElectronicBilling::COUNTRY_CODE,
                'ubigeo'                             => '',
                'direccion'                          => $this->purchase->payment_gateway_info->address,
                'correo_electronico'                 => $this->purchase->payment_gateway_info->email,
                'telefono'                           => $this->purchase->payment_gateway_info->phone,
            ]),
            'totales'                      => collect([
                'total_exportacion'            => 0.00,
                'total_operaciones_gravadas'   => $this->purchaseTypeData->total * (1 - floatval($this->config['facigv'])),
                'total_operaciones_inafectas'  => 0.00,
                'total_operaciones_exoneradas' => 0.00,
                'total_operaciones_gratuitas'  => 0.00,
                'total_igv'                    => $this->purchaseTypeData->total * (floatval($this->config['facigv'])),
                'total_impuestos'              => $this->purchaseTypeData->total * (floatval($this->config['facigv'])),
                'total_valor'                  => $this->purchaseTypeData->total * (1 - floatval($this->config['facigv'])),
                'total_venta'                  => $this->purchaseTypeData->total
            ]),
            'items'                        => $itemsCollect,
            'acciones'                     => collect([
                'enviar_email'       => false,
                'enviar_xml_firmado' => false,
                'formato_pdf'        => 'ticket'
            ])
        ]);
    }

    private function buildItemsDataForTickets(): Collection
    {
        $tickets = Ticket::with(['purchaseItem'])
            ->where('purchase_id', $this->purchase->id)
            ->get();

        $items = [];
        foreach ($tickets as $key => $item) {
            array_push($items, [
                "codigo_interno"             => 'item' . $key,
                "descripcion"                => 'Butaca ' . $item->seat_name,
                "codigo_producto_sunat"      => "",
                "unidad_de_medida"           => "ZZ",
                "cantidad"                   => 1,
                "valor_unitario"             => $item->purchaseItem->paid_amount * (1 - floatval($this->config['facigv'])), // sin igv
                "codigo_tipo_precio"         => ElectronicBilling::PRICE_TYPE_CODE,
                "precio_unitario"            => $item->purchaseItem->paid_amount, //con igv
                "codigo_tipo_afectacion_igv" => ElectronicBilling::IGV_CODE,
                "total_base_igv"             => $item->purchaseItem->paid_amount * (1 - floatval($this->config['facigv'])),
                "porcentaje_igv"             => floatval($this->config['facigv']) * 100,
                "total_igv"                  => $item->purchaseItem->paid_amount * (floatval($this->config['facigv'])),
                "total_impuestos"            => $item->purchaseItem->paid_amount * (floatval($this->config['facigv'])),
                "total_valor_item"           => $item->purchaseItem->paid_amount * (1 - floatval($this->config['facigv'])),
                "total_item"                 => $item->purchaseItem->paid_amount
            ]);
        }
        return collect($items);
    }

    private function buildItemsDataForSweets(): Collection
    {
        $sweets = SweetSold::with(['purchaseItem'])
            ->where('purchase_id', $this->purchase->id)
            ->get();

        $uniqueSweets = $sweets->unique('code');

        $items = [];
        $index = 0;
        foreach ($uniqueSweets as $item) {

            $index += 1;
            $quantity = $sweets->where('code', $item->code)->count();
            $total = $item->price * $quantity;

            array_push($items, [
                "codigo_interno"             => 'item' . $index,
                "descripcion"                => $item->name,
                "codigo_producto_sunat"      => "",
                "unidad_de_medida"           => "NIU",
                "cantidad"                   => $quantity,
                "valor_unitario"             => $item->price * (1 - floatval($this->config['facigv'])), // precio unitario sin igv
                "codigo_tipo_precio"         => ElectronicBilling::PRICE_TYPE_CODE,
                "precio_unitario"            => $item->price, // precio unitario con igv
                "codigo_tipo_afectacion_igv" => ElectronicBilling::IGV_CODE,
                "total_base_igv"             => $total * (1 - floatval($this->config['facigv'])), // igv de precio total
                "porcentaje_igv"             => floatval($this->config['facigv']) * 100,
                "total_igv"                  => $total * (floatval($this->config['facigv'])),
                "total_impuestos"            => $total * (floatval($this->config['facigv'])),
                "total_valor_item"           => $total * (1 - floatval($this->config['facigv'])),
                "total_item"                 => $total
            ]);
        }
        return collect($items);
    }

    private function updatePurchaseVoucherWithResponseData($response, $request)
    {
        $responseToSave = $response;
        $responseToSave['data']['qr'] = 'valor suprimido por cinestar debido a ser demasiado largo';

        $this->purchaseVoucher->update(
            [
                'external_id' => $response['data']['external_id'],
                'hash'        => $response['data']['hash'],
                'link_xml'    => $response['links']['xml'],
                'link_pdf'    => $response['links']['pdf'],
                'link_cdr'    => $response['links']['cdr'],
                'send_fe'     => GlobalEnum::COMPLETED_STATUS,
                'request'     => json_encode($request),
                'response'    => json_encode($responseToSave)
            ]
        );

        $this->purchaseTypeData->send_fe = GlobalEnum::COMPLETED_STATUS;
        $this->purchaseTypeData->save();
    }

    private function updatePurchaseVoucherWithoutCallBillingApi()
    {
        $this->purchaseVoucher->update(
            [
                'external_id' => "",
                'hash'        => Str::uuid()->toString(),
                'link_xml'    => "",
                'link_pdf'    => "",
                'link_cdr'    => "",
                'send_fe'     => GlobalEnum::COMPLETED_STATUS,
                'request'     => json_encode("{}"),
                'response'    => json_encode("{}")
            ]
        );

        $this->purchaseTypeData->send_fe = GlobalEnum::COMPLETED_STATUS;
        $this->purchaseTypeData->save();
    }

    private function formatConfig($setting)
    {
        $config = $setting->config;
        $config['facigv'] = $config['facigv'] / 100;
        return $config;
    }
}
