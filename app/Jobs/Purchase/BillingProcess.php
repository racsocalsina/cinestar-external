<?php


namespace App\Jobs\Purchase;


use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
//use App\Events\BillingProcessCompleted;

class BillingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PurchaseRepositoryInterface $purchaseRepository;
    private BillingRepositoryInterface $billingRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;
    private $hasError = false;
    private $purchase;

    public function __construct(
        Purchase                            $purchase,
        PurchaseRepositoryInterface         $purchaseRepository,
        BillingRepositoryInterface          $billingRepository,
        PurchaseInternalRepositoryInterface $purchaseInternalRepository
    )
    {
        $this->purchase = $purchase;
        $this->billingRepository = $billingRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;
    }

    public function handle()
    {
        $this->processPurchaseTickets();
        $this->processPurchaseSweets();
        //event(new BillingProcessCompleted($this->purchase->id));
    }

    public function processPurchaseTickets(): void
    {
        $purchaseVoucher = PurchaseVoucher::where('purchase_id', $this->purchase->id)
            ->whereNotNull('purchase_ticket_id')
            ->where(function ($query) {
                $query->whereNull('send_fe')
                    ->orwhere('send_fe', PurchaseStatus::COMPLETED);
            })
            ->whereHas('purchase_ticket', function ($query) {
                $query->whereNull('send_internal')
                    ->orwhere('send_internal', PurchaseStatus::COMPLETED);
            })
            ->first();
        if ($purchaseVoucher) {
            try {
                DB::beginTransaction();
                $this->billingRepository->callApi($purchaseVoucher);
                DB::commit();
                SendPurchaseInternal::dispatch(
                    $purchaseVoucher,
                    $this->purchaseRepository,
                    $this->purchaseInternalRepository
                );

            } catch (\Exception $exception) {
                DB::rollBack();
                $this->hasError = true;
                $this->purchaseRepository->updateAsError(PurchaseStatus::ERROR_BILLING, $this->purchase, null, $exception);
                $data = [ 'error_event_history' => json_encode([PurchaseStatus::ERROR_BILLING => 'true'])];
                $this->purchaseRepository->updateStatusTransaction($this->purchase->id, $data);
            }
        }
    }

    public function processPurchaseSweets(): void
    {
        $purchaseVoucher = PurchaseVoucher::where('purchase_id', $this->purchase->id)
            ->whereNotNull('purchase_sweet_id')
            ->where(function ($query) {
                $query->whereNull('send_fe')
                    ->orwhere('send_fe', PurchaseStatus::COMPLETED);
            })
            ->whereHas('purchase_sweet', function ($query) {
                $query->whereNull('send_internal')
                    ->orwhere('send_internal', PurchaseStatus::COMPLETED);
            })
            ->first();

        if ($purchaseVoucher) {
            try {
                DB::beginTransaction();
                $this->billingRepository->callApi($purchaseVoucher);
                DB::commit();

                SendPurchaseInternal::dispatch(
                    $purchaseVoucher,
                    $this->purchaseRepository,
                    $this->purchaseInternalRepository
                );
            } catch (\Exception $exception) {
                DB::rollBack();
                $this->hasError = true;
                $this->purchaseRepository->updateAsError(PurchaseStatus::ERROR_BILLING, $this->purchase, null, $exception);
                $data = [ 'error_event_history' => json_encode([PurchaseStatus::ERROR_BILLING => 'true'])];
                $this->purchaseRepository->updateStatusTransaction($this->purchase->id, $data);
            }
        }
    }

}
