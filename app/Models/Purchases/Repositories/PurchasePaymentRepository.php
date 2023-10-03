<?php


namespace App\Models\Purchases\Repositories;


use App\Enums\SalesType;
use App\Enums\TicketStatus;
use App\Exceptions\PurchaseExceptionNotCompleted;
use App\Helpers\FunctionHelper;
use App\Jobs\Purchase\BillingProcess;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Models\AutoIncrementCodes\Service\InvoiceSequenceFactory;
use App\Models\Cards\Card;
use App\Models\PaymentGateways\Interfaces\PaymentGatewayInfoRepositoryInterface;
use App\Models\PointsHistory\Repositories\Interfaces\PointHistoryRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\Tickets\Ticket;
use App\Services\PayU\TransactionProcess\Actions\AuthorizationAndCapture;
use App\Services\PayU\TransactionProcess\Dtos\AuthorizeAndCaptureDto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\PurchaseStatusTransaction;
use App\Enums\PurchaseStatus;

class PurchasePaymentRepository implements PurchasePaymentRepositoryInterface
{
    private PaymentGatewayInfoRepositoryInterface $paymentGatewayInfoRepository;
    private PurchaseRepositoryInterface $purchaseRepository;
    private AuthorizationAndCapture $authorizationAndCapture;
    private SettingRepositoryInterface $settingRepository;
    private PointHistoryRepositoryInterface $pointHistoryRepository;
    private BillingRepositoryInterface $billingRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;

    private $purchase;
    private $body;
    private $paymentGatewayResponse;
    private $card;
    private $referenceCode;
    private $purchaseIsFree;
    private $user;
    private $pickup_date;

    public function __construct(
        PaymentGatewayInfoRepositoryInterface $paymentGatewayInfoRepository,
        PurchaseRepositoryInterface $purchaseRepository,
        AuthorizationAndCapture $authorizationAndCapture,
        PointHistoryRepositoryInterface $pointHistoryRepository,
        SettingRepositoryInterface $settingRepository,
        BillingRepositoryInterface $billingRepository,
        PurchaseInternalRepositoryInterface $purchaseInternalRepository
    )
    {
        $this->paymentGatewayInfoRepository = $paymentGatewayInfoRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->authorizationAndCapture = $authorizationAndCapture;
        $this->pointHistoryRepository = $pointHistoryRepository;
        $this->settingRepository = $settingRepository;
        $this->billingRepository = $billingRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;
    }

    public function pay($purchase, $body, $fromBo = false)
    {
        $this->nonTransactionalProcesses($purchase, $body, $fromBo);
        return $this->transactionalProcesses();
    }

    private function nonTransactionalProcesses($purchase, $body, $fromBo)
    {
        $this->purchase = $purchase;
        $this->body = $body;

        if(isset($body["pickup_date"])){
            $this->pickup_date = $body["pickup_date"];
        }

        if($fromBo) {
            $this->user = $this->purchase->user;
        } else {
            $this->user = FunctionHelper::getApiUser();
            $this->referenceCode = $this->generateReferenceCode();
            $this->purchaseIsFree = $this->purchaseRepository->purchaseIsFree($purchase);
            $data = ['transaction_status' => PurchaseStatusTransaction::PAYMENT_IN_PROCESS];
            $this->purchaseRepository->updateStatusTransaction($this->purchase->id, $data);
            $this->savePaymentGatewayInfo(); // GUARDA INFO DE PASARELA
            $this->callPaymentGatewayApi(); // MAPEO DE LA RESPUESTA DE PAYU
            $this->updatePurchaseAsConfirmed($this->purchase, $this->paymentGatewayResponse);
        }
    }

    public function transactionalProcesses()
    {
        
        try {
            DB::beginTransaction();
            $this->reserveSerialNumberByType();
            $this->pointsProcess();
            $this->markCodesAsUsed();
            $this->finalUpdateProcess();
            $this->runJobs();
            $return = $this->getPurchasePaymentData();
            DB::commit();
            return $return;
        } catch (\Exception $exception) {
            logger($exception);
            DB::rollBack();
            throw new PurchaseExceptionNotCompleted("Error al procesar el flujo de pago");
        }
    }

    private function savePaymentGatewayInfo()
    {
        if ($this->userIsAuthenticated()) {
            $this->card = Card::where('token', $this->body['cc_data']['token'])->first();
        } else {
            $card = new Card();
            $card->payment_method = $this->body['cc_data']['payment_method'];
            $card->masked_number = $this->maskCC();
            $card->full_name = $this->body['cc_data']['full_name'];
            $this->card = $card;
        }

        $dataToPaymentGatewayInfo = $this->body;
        $dataToPaymentGatewayInfo['payment_gateway_name'] = 'payu';
        $dataToPaymentGatewayInfo['extra_data'] = $this->getExtraData();
        $dataToPaymentGatewayInfo['reference_code'] = $this->referenceCode;
        $this->paymentGatewayInfoRepository->deleteByPurchase($this->body['purchase_id']);

        return $this->paymentGatewayInfoRepository->create($dataToPaymentGatewayInfo);
    }

    private function getExtraData()
    {
        return json_encode([
            'payment_method'            => $this->purchaseIsFree ? 'Canjeado' : $this->card->payment_method,
            'credit_card_masked_number' => $this->purchaseIsFree ? null : $this->card->masked_number,
            'currency'                  => 'PEN',
        ]);
    }

    private function callPaymentGatewayApi(): void
    {
        if ($this->purchaseIsFree) {
            $this->paymentGatewayResponse = json_encode($this->settingRepository->paymentGatewayResponse());
            return;
        }

        $this->purchase = $this->purchase->loadMissing([
            'headquarter', 'payment_gateway_info', 'user.customer'
        ]);

        $dto = new AuthorizeAndCaptureDto();
        $dto->setBusinessName($this->purchase->headquarter->business_name);
        $dto->setReferenceCode($this->referenceCode);
        $dto->setDescription("transaccion de la compra {$this->purchase->id}");
        $dto->setAmount($this->purchase->amount);
        $dto->setPayerFullName($this->purchase->payment_gateway_info->full_name);
        $dto->setPayerEmail($this->purchase->payment_gateway_info->email);
        $dto->setPayerContactPhone($this->purchase->payment_gateway_info->phone);
        $dto->setPayerDocumentNumber($this->purchase->payment_gateway_info->document_number);
        $dto->setPayerBillingAddressStreet1($this->purchase->payment_gateway_info->address);
        $dto->setPaymentMethod($this->card->payment_method);
        $dto->setDeviceSessionId($this->body['device_session_id']);

        if ($this->userIsAuthenticated()) {
            $dto->setBuyerId($this->purchase->user->id);
            $dto->setBuyerFullName($this->purchase->user->customer->full_name);
            $dto->setBuyerEmail($this->purchase->user->customer->email);
            $dto->setBuyerContactPhone($this->purchase->user->customer->cellphone);
            $dto->setBuyerDocumentNumber($this->purchase->user->customer->document_number);
            $dto->setPayerId($this->purchase->user->id);

            $dto->setCCTokenId($this->card->token);
            $dto->setCCNumber(null);
            $dto->setCCExpirationDate(null);
            $dto->setCCName($this->card->full_name);
            $dto->setCCSecurityCode($this->body['cc_data']['security_code']);
        } else {
            $dto->setBuyerId(null);
            $dto->setBuyerFullName($this->purchase->payment_gateway_info->full_name);
            $dto->setBuyerEmail($this->purchase->payment_gateway_info->email);
            $dto->setBuyerContactPhone($this->purchase->payment_gateway_info->phone);
            $dto->setBuyerDocumentNumber($this->purchase->payment_gateway_info->document_number);
            $dto->setPayerId(null);

            $dto->setCCTokenId(null);
            $dto->setCCNumber($this->body['cc_data']['number']);
            $dto->setCCExpirationDate($this->body['cc_data']['expiration_date']);
            $dto->setCCName($this->body['cc_data']['full_name']);
            $dto->setCCSecurityCode($this->body['cc_data']['security_code']);
        }

        $this->paymentGatewayResponse = $this->authorizationAndCapture->execute($dto);

    }

    public function updatePurchaseAsConfirmed($purchase, $paymentGatewayResponse = null): void
    {
        // this is only when an error has occurred
        if($paymentGatewayResponse == null)
            $paymentGatewayResponse = "{}";

        $this->purchaseRepository->updateAsConfirmed($purchase, $paymentGatewayResponse);
    }

    private function getPurchasePaymentData()
    {
        return $this->purchaseRepository->getPurchasePaymentData($this->purchase->id);
    }

    private function generateReferenceCode(): string
    {
        return now()->timestamp . '-' . $this->purchase->id;
    }

    private function pointsProcess()
    {
        if (!$this->user)
            return;

        if ($this->user->customer->socio_cod && $this->user->customer->user_partner_cod) {
            $this->pointHistoryRepository->store($this->purchase);
        }
    }

    private function markCodesAsUsed()
    {
        if ($this->purchase->promotions->count()) {
            $promotions = $this->purchase->promotions->where('replace_type', TicketPromotion::class)->whereNotNull('codes');
            foreach ($promotions as $i => $promotion) {
                foreach ($promotion->codes as $o => $code) {
                    DB::connection('cinestar_socios')->table('qmaecod')->where('codigo', $code)->update(array(
                        'fecha_modificacion' => now(),
                        'estado'             => 1,
                        'serie'              => $this->purchase->purchase_ticket->remote_movkey
                    ));
                }
            }
        }
    }

    private function runJobs()
    {
        BillingProcess::dispatch(
            $this->purchase,
            $this->purchaseRepository,
            $this->billingRepository,
            $this->purchaseInternalRepository
        );

        $config = $this->settingRepository->getSystemConfiguration();
        if ($config)
            if (isset($config['url_info_receipt']))
                $config = ['url_info_receipt' => $config['url_info_receipt']];

        SendPurchaseEmail::dispatch($this->purchase, $config);
    }

    /*
    * Esta funcion reserva los numeros de serie por el tipo de compra (sweet y ticket)
    * Nota: por cada tipo mencionado se genera numeros de series diferentes
    */
    private function reserveSerialNumberByType(): void
    {
        // ticket
        $purchaseTicket = PurchaseTicket::where('purchase_id', $this->purchase->id)
            ->whereNull('remote_movkey')
            ->first();

        if ($purchaseTicket)
            $this->reserveSerialNumber(SalesType::TICKET, $purchaseTicket);

        // sweet
        $purchaseSweets = PurchaseSweet::where('purchase_id', $this->purchase->id)
            ->whereNull('remote_movkey')
            ->first();

        if ($purchaseSweets)
            $this->reserveSerialNumber(SalesType::SWEET, $purchaseSweets);
    }

    public function reserveSerialNumber($salesType, $purchaseTypeData): void
    {
        $purchaseTypeData = $purchaseTypeData->refresh();
        $purchase = $purchaseTypeData->purchase;
        $headquarter = $purchase->headquarter;

        $serialNumberDataGenerated = InvoiceSequenceFactory::reserveNextCode(
            $purchase->voucher_type,
            $headquarter->point_sale,
            FunctionHelper::getShopCodeBySalesType($salesType),
            $headquarter->business_name
        );

        $this->saveRemoteMovkey($purchaseTypeData, $serialNumberDataGenerated);
        $this->savePurchaseVoucher($salesType, $purchaseTypeData, $serialNumberDataGenerated);
    }

    private function saveRemoteMovkey($purchaseTypeData, $serialNumberDataGenerated): void
    {
        $remoteMovKey = $serialNumberDataGenerated['internal_serial_number'] . '-' . $serialNumberDataGenerated['document_number'];
        $purchaseTypeData->remote_movkey = $remoteMovKey;
        $purchaseTypeData->headquarter_id = $this->purchase->headquarter_id;
        $purchaseTypeData->save();
    }

    private function savePurchaseVoucher($salesType, $purchaseTypeData, $serialNumberDataGenerated): void
    {
        $dateNow = Carbon::now();
        $field = $salesType == SalesType::TICKET ? 'purchase_ticket_id' : 'purchase_sweet_id';

        PurchaseVoucher::create([
            'purchase_id'            => $purchaseTypeData->purchase_id,
            $field                   => $purchaseTypeData->id,
            'serial_number'          => $serialNumberDataGenerated['fe_serial_number'],
            'internal_serial_number' => $serialNumberDataGenerated['internal_serial_number'],
            'document_number'        => $serialNumberDataGenerated['document_number'],
            'headquarter_id'         => $this->purchase->headquarter_id,
            'date_issue'             => $dateNow
        ]);
    }

    private function finalUpdateProcess(): void
    {
        Ticket::where('purchase_id', $this->purchase->id)
            ->update([
                'status' => TicketStatus::COMPLETED
            ]);
            # Aqui se evalua si el ticket solo es de chocolateria o tambien es por una compara de Pelicula
        $movieTime = $this->purchase->movie_time;

        if ($movieTime) {
            PurchaseTicket::where('purchase_id', $this->purchase->id)
                ->update(['function_date' => $movieTime->start_at]);

            PurchaseSweet::where('purchase_id', $this->purchase->id)
                ->update(['pickup_date' => $movieTime->start_at->format('Y-m-d')]);
        }elseif(isset($this->pickup_date)){
            PurchaseSweet::where('purchase_id', $this->purchase->id)
            ->update(['pickup_date' => $this->pickup_date]);
        } 
        else {
            # Aqui se le indica la fecha de creación de la compra de Chocolateria
            PurchaseSweet::where('purchase_id', $this->purchase->id)
                ->update(['pickup_date' => now()->format('Y-m-d')]);
        }
    }

    private function userIsAuthenticated(): bool
    {
        return $this->user != null;
    }

    private function maskCC(): string
    {
        $number = $this->body['cc_data']['number'];
        $bin = substr($number, 0, 6);
        $last = substr($number, strlen($number) - 4, 4);
        $hiddenCount = strlen($number) - 10;
        $mask = str_repeat('*', $hiddenCount);
        return "${bin}${mask}${last}";
    }
}
