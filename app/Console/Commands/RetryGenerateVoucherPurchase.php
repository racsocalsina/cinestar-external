<?php

namespace App\Console\Commands;

use App\Enums\PurchaseStatus;
use App\Enums\SalesType;
use App\Enums\TicketStatus;
use App\Exceptions\PurchaseExceptionNotCompleted;
use App\Jobs\Purchase\BillingProcess;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\BillingRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseInternalRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\Tickets\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RetryGenerateVoucherPurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:voucher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar voucher de las compras pagadas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $paymentRepository;
    private PurchaseRepositoryInterface $purchaseRepository;
    private BillingRepositoryInterface $billingRepository;
    private PurchaseInternalRepositoryInterface $purchaseInternalRepository;

    public function __construct(PurchasePaymentRepositoryInterface  $paymentRepository,
                                PurchaseRepositoryInterface         $purchaseRepository,
                                BillingRepositoryInterface          $billingRepository,
                                PurchaseInternalRepositoryInterface $purchaseInternalRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->billingRepository = $billingRepository;
        $this->purchaseInternalRepository = $purchaseInternalRepository;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $purchases = Purchase::where('confirmed', true)
            ->whereDoesntHave('purchase_voucher')->where('status', PurchaseStatus::CONFIRMED)->get();
//        foreach ($purchases as $purchase) {
//            try {
//                DB::beginTransaction();
//                $purchaseTicket = PurchaseTicket::where('purchase_id', $purchase->id)
//                    ->whereNull('remote_movkey')
//                    ->first();
//
//                if ($purchaseTicket)
//                    $this->paymentRepository->reserveSerialNumber(SalesType::TICKET, $purchaseTicket);
//
//                // sweet
//                $purchaseSweets = PurchaseSweet::where('purchase_id', $purchase->id)
//                    ->whereNull('remote_movkey')
//                    ->first();
//
//                if ($purchaseSweets)
//                    $this->paymentRepository->reserveSerialNumber(SalesType::SWEET, $purchaseSweets);
//
//                $this->markCodesAsUsed($purchase);
//
//                $this->finalUpdateProcess($purchase);
//                $this->runJobs($purchase);
//                DB::commit();
//
//            } catch (\Exception $exception) {
//                logger($exception);
//                DB::rollBack();
//                throw new PurchaseExceptionNotCompleted("Error al procesar el flujo de pago");
//            }
//        }
        return 0;
    }

    private function markCodesAsUsed($purchase)
    {
        if ($purchase->promotions->count()) {
            $promotions = $purchase->promotions->where('replace_type', TicketPromotion::class)->whereNotNull('codes');
            foreach ($promotions as $i => $promotion) {
                foreach ($promotion->codes as $o => $code) {
                    DB::connection('cinestar_socios')->table('qmaecod')->where('codigo', $code)->update(array(
                        'fecha_modificacion' => now(),
                        'estado' => 1,
                        'serie' => $purchase->purchase_ticket->remote_movkey
                    ));
                }
            }
        }
    }

    private function finalUpdateProcess($purchase): void
    {
        Ticket::where('purchase_id', $purchase->id)
            ->update([
                'status' => TicketStatus::COMPLETED
            ]);

        $movieTime = $purchase->movie_time;

        if ($movieTime) {
            PurchaseTicket::where('purchase_id', $purchase->id)
                ->update(['function_date' => $movieTime->start_at]);

            PurchaseSweet::where('purchase_id', $purchase->id)
                ->update(['pickup_date' => $movieTime->start_at->format('Y-m-d')]);
        } else {
            PurchaseSweet::where('purchase_id', $purchase->id)
                ->update(['pickup_date' => now()->format('Y-m-d')]);
        }
    }


    private function runJobs($purchase)
    {
        BillingProcess::dispatch(
            $purchase,
            $this->purchaseRepository,
            $this->billingRepository,
            $this->purchaseInternalRepository
        );

        SendPurchaseEmail::dispatch($purchase);
    }

}
