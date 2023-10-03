<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponser;
use App\Enums\PurchaseStatus;
use App\Enums\PurchaseStatusTransaction;
use Illuminate\Support\Carbon;
use App\Models\Purchases\Purchase;
use App\Jobs\Purchase\BillingProcess;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Jobs\Purchase\SendPurchaseInternal;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;


class RetryFailedPurchases extends Command
{
    use ApiResponser;

    protected $signature = 'retry:failed-purchases';

    protected $description = 'Retry failed purchases';

    private $purchaseRepository;
    private $billingRepository;
    private $purchaseInternalRepository;
    private $purchasePaymentRepository;
    private $settingRepository;

    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        BillingRepositoryInterface $billingRepository,
        PurchaseInternalRepositoryInterface $purchaseInternalRepository,
        PurchasePaymentRepositoryInterface $purchasePaymentRepository,
        SettingRepositoryInterface $settingRepository
    )
    {
        parent::__construct();

        $this->purchaseRepository = $purchaseRepository;
        $this->billingRepository = $billingRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;
        $this->purchasePaymentRepository = $purchasePaymentRepository;
        $this->settingRepository = $settingRepository;
    }
    
    public function handle()
    {
        $now = Carbon::now()->subMinutes(30);

        $purchases = Purchase::where(function ($query) use ($now) {
            $query->where('status', 'error-billing')
                ->orWhere('status', 'confirmed')
                ->orWhere('status', 'error-internal')
                ->orWhere('status', 'error');
        })
        ->whereHas('movie_time', function ($query) use ($now) {
            $query->where('start_at', '>=', $now);
        })
        ->get();
        $purchases = $purchases->unique('id')->values();
        Log::info("ID PURCHASES IN PROCESS - ".json_encode($purchases->pluck('id')->toArray()));

        foreach ($purchases as $purchase) {

            try {
                $purchaseVoucher = PurchaseVoucher::where('purchase_id', $purchase->id)->first();
                $purchase->retries += 1;
	            $purchase->save();

                if($purchaseVoucher == null)
                {
                    if($purchase->status == PurchaseStatus::CONFIRMED || $purchase->status == PurchaseStatus::ERROR){
                        $this->purchasePaymentRepository->pay($purchase, null, true);
                    }
                } else {
                    $config = $this->settingRepository->getSystemConfiguration();
                    if ($config)
                        if (isset($config['url_info_receipt']))
                            $config = ['url_info_receipt' => $config['url_info_receipt']];

                    if($purchase->status == PurchaseStatus::ERROR_BILLING || $purchase->status == PurchaseStatus::CONFIRMED){
                        $this->runBillingProcess($purchase);

                        if($purchase->transaction_status != PurchaseStatusTransaction::TICKET_SENT){
                            Log::info("ENVIO EMAIL - {$purchase->id}");
                            SendPurchaseEmail::dispatch($purchase, $config);
                        }
                    }

                    if($purchase->status == PurchaseStatus::ERROR_INTERNAL){
                        $this->runInternalProcess($purchase);

                        if($purchase->transaction_status != PurchaseStatusTransaction::TICKET_SENT){
                            Log::info("ENVIO EMAIL - {$purchase->id}");
                            SendPurchaseEmail::dispatch($purchase, $config);
                        }
                    }
                }
            } catch (\Exception $exception) {
                logger($exception);
            }
        }
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
