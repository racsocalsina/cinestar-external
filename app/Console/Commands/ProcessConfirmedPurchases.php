<?php


namespace App\Console\Commands;


use App\Enums\PurchaseStatus;
use App\Jobs\Purchase\BillingProcess;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessConfirmedPurchases extends Command
{
    private PurchaseRepositoryInterface $purchaseRepository;
    private BillingRepositoryInterface $billingRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;

    public function __construct(
        PurchaseRepositoryInterface         $purchaseRepository,
        BillingRepositoryInterface          $billingRepository,
        PurchaseInternalRepositoryInterface $purchaseInternalRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->billingRepository = $billingRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:process-confirmed-purchases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Get confirmed purchases that were not processed or sent to the ERP by queue problem (redis)
     * and so avoid that the ERP does not found this purchases in its database.
     * Backoffice Problem: Purchase module > filter by confirmed status (these records do not exist in the ERP)
     *
     * A lapse of 30 minutes to get these purchases, since of creation of purchase, if exists data then these purchases founded
     * are considered as were not processed correctly
     *
     * @return int
     */
    public function handle()
    {
        /*
         * Calculate confirmed purchases that were not processed or sen
         */

        $purchases = $this->purchaseTicket();
        $purchases = $purchases->merge($this->purchaseSweet());
        $purchases = $purchases->unique('id')->values();
        Log::info("ID PURCHASES PROCESS CONFIRMED - ".json_encode($purchases->pluck('id')->toArray()));
        foreach ($purchases as $purchase) {
            BillingProcess::dispatch(
                $purchase,
                $this->purchaseRepository,
                $this->billingRepository,
                $this->purchaseInternalRepository
            );

            SendPurchaseEmail::dispatch($purchase);
        }

        return 0;
    }

    private function purchaseTicket()
    {
        return Purchase::where('status', PurchaseStatus::CONFIRMED)
            ->whereRaw("TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 30")
            ->whereHas('purchase_voucher', function ($query) {
                $query->whereNotNull('purchase_ticket_id')
                    ->where(function ($query) {
                        $query->whereNull('send_fe')
                            ->orwhere('send_fe', PurchaseStatus::COMPLETED);
                    })
                    ->whereHas('purchase_ticket', function ($query) {
                        $query->whereNull('send_internal')
                            ->orwhere('send_internal', PurchaseStatus::COMPLETED);
                    });
            })
            ->get();
    }

    private function purchaseSweet()
    {
        return Purchase::where('status', PurchaseStatus::CONFIRMED)
            ->whereRaw("TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 30")
            ->whereHas('purchase_voucher', function ($query) {
                $query->whereNotNull('purchase_sweet_id')
                    ->where(function ($query) {
                        $query->whereNull('send_fe')
                            ->orwhere('send_fe', PurchaseStatus::COMPLETED);
                    })
                    ->whereHas('purchase_sweet', function ($query) {
                        $query->whereNull('send_internal')
                            ->orwhere('send_internal', PurchaseStatus::COMPLETED);
                    });
            })
            ->get();
    }
}
