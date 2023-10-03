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

class RunConfirmedPurchases extends Command
{
    private PurchaseRepositoryInterface $purchaseRepository;
    private BillingRepositoryInterface $billingRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;

    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        BillingRepositoryInterface $billingRepository,
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
    protected $signature = 'run:confirmed-purchases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $purchases = Purchase::where('status', PurchaseStatus::CONFIRMED)->get();

        foreach ($purchases as $purchase)
        {
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
}
