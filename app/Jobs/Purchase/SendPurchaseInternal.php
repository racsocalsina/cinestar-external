<?php

namespace App\Jobs\Purchase;

use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseVoucher\PurchaseVoucher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPurchaseInternal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $purchase;
    private $purchaseVoucher;
    private PurchaseRepositoryInterface $purchaseRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;

    public function __construct(PurchaseVoucher $purchaseVoucher,
                                PurchaseRepositoryInterface $purchaseRepository,
                                PurchaseInternalRepositoryInterface $purchaseInternalRepository)
    {
        $this->purchaseVoucher = $purchaseVoucher;
        $this->purchaseRepository = $purchaseRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            //$this->purchase = Purchase::find($this->purchaseVoucher->purchase_id);
            $this->purchase = Purchase::where('id', $this->purchaseVoucher->purchase_id)->lockForUpdate()->first();
            $this->purchaseInternalRepository->sendPurchaseDataToInternal($this->purchaseVoucher->refresh());
            $this->updateToCompleted();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->purchaseRepository->updateAsError(PurchaseStatus::ERROR_INTERNAL, $this->purchase, null, $exception);
            $data = [ 'error_event_history' => json_encode([PurchaseStatus::ERROR_INTERNAL => 'true'])];
            $this->purchaseRepository->updateStatusTransaction($this->purchase->id, $data);
            Log::error('Queue failed');
            Log::error($exception->getMessage());
        }
    }

    private function updateToCompleted()
    {
        if ($this->purchase) {
            $this->purchase->status = PurchaseStatus::COMPLETED;
            $this->purchase->save();
        }
    }
}
