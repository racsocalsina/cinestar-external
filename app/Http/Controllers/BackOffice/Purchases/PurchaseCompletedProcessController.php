<?php


namespace App\Http\Controllers\BackOffice\Purchases;


use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Jobs\Purchase\BillingProcess;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Jobs\Purchase\SendPurchaseInternal;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Log;

class PurchaseCompletedProcessController extends Controller
{
    use ApiResponser;

    private $purchaseRepository;
    private $billingRepository;
    private $purchaseInternalRepository;
    private $purchasePaymentRepository;

    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        BillingRepositoryInterface $billingRepository,
        PurchaseInternalRepositoryInterface $purchaseInternalRepository,
        PurchasePaymentRepositoryInterface $purchasePaymentRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->billingRepository = $billingRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;
        $this->purchasePaymentRepository = $purchasePaymentRepository;
        $this->middleware('permission:read-reports');
    }

    public function __invoke(Purchase $purchase)
    {
        $purchase = Purchase::where('id', $purchase->id)->first();
        $purchaseVoucher = PurchaseVoucher::where('purchase_id', $purchase->id)->first();
        $purchase->retries += 1;
	    $purchase->save();

        if($purchaseVoucher == null)
        {

            if($purchase->status == PurchaseStatus::CONFIRMED){
                $this->purchasePaymentRepository->pay($purchase, null, true);
                return $this->success();
            }
        } else {
            $settingRepository = \App::make(SettingRepositoryInterface::class);
            $config = $settingRepository->getSystemConfiguration();
            if ($config)
                if (isset($config['url_info_receipt']))
                    $config = ['url_info_receipt' => $config['url_info_receipt']];

            if($purchase->status == PurchaseStatus::ERROR_BILLING || $purchase->status == PurchaseStatus::CONFIRMED){
                $this->runBillingProcess($purchase);
                Log::info("ENVIO EMAIL - {$purchase->id}");
                SendPurchaseEmail::dispatch($purchase, $config);
                return $this->success();
            }

            if($purchase->status == PurchaseStatus::ERROR_INTERNAL){
                $this->runInternalProcess($purchase);
                SendPurchaseEmail::dispatch($purchase, $config);
                return $this->success();
            }

        }

        return $this->errorResponse(['message' => 'Esta compra no tiene un estado permitido para ejecutar este proceso'], 422);
    }

    private function runBillingProcess($purchase)
    {
        BillingProcess::dispatch(
            $purchase,
            $this->purchaseRepository,
            $this->billingRepository,
            $this->purchaseInternalRepository
        );
    }

    private function runInternalProcess($purchase)
    {
        Log::info("RUN INTERNAL");
        $this->internalProcessPurchaseTickets($purchase);
        $this->internalProcessPurchaseSweets($purchase);
    }

    private function internalProcessPurchaseTickets($purchase): void
    {
        $purchaseTicket = PurchaseTicket::where('purchase_id', $purchase->id)
            ->whereNull('send_internal')
            ->first();

        if ($purchaseTicket) {
            $purchaseVoucher = PurchaseVoucher::where('purchase_id', $purchase->id)
                ->whereNotNull('purchase_ticket_id')
                ->first();
            SendPurchaseInternal::dispatch($purchaseVoucher, $this->purchaseRepository, $this->purchaseInternalRepository);
        }
    }

    private function internalProcessPurchaseSweets($purchase): void
    {
        $purchaseSweets = PurchaseSweet::where('purchase_id', $purchase->id)
            ->whereNull('send_internal')
            ->first();

        if ($purchaseSweets) {
            $purchaseVoucher = PurchaseVoucher::where('purchase_id', $purchase->id)
                ->whereNotNull('purchase_sweet_id')
                ->first();
            SendPurchaseInternal::dispatch($purchaseVoucher, $this->purchaseRepository, $this->purchaseInternalRepository);
        }
    }

}
