<?php


namespace App\Console\Commands;


use App\Enums\PurchaseStatus;
use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseLockedSeats extends Command
{
    protected $signature = 'purchase:release-ls';
    protected $description = 'Delete pending purchases of tickets and release locked seats that were not purchased';

    public $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        parent::__construct();
        $this->purchaseRepository = $purchaseRepository;
    }

    public function handle()
    {
        $this->processPurchasesOfTickets();
    }

    private function processPurchasesOfTickets()
    {
        $purchases = Purchase::withTrashed()
            ->where('confirmed', false)
            ->whereHas('purchase_ticket')
            ->get();

        $purchases->map(function ($purchase)
        {
            try {
                if($this->checkIfPurchaseHasExpired($purchase)){
                    $this->purchaseRepository->destroy($purchase->id);
                }
            } catch (\Exception $exception) {
                $message = "Error en el comando ReleaseLockedSeats con id " . $purchase->id;
                $exceptionDto = (new BuildExceptionDto($exception))->build();
                $exceptionDto->setSubject($message);
                $exceptionDto->setMessage($exception->getMessage());
//                SendErrorEmail::dispatch($exceptionDto);
                FunctionHelper::sendErrorMail($exceptionDto);
            }
        });
    }

    private function checkIfPurchaseHasExpired($purchase)
    {
        return Carbon::parse($purchase->created_at)
            ->addMinutes($this->getEstimatedMinutesToPurchase())
            ->isPast();
    }

    private function getEstimatedMinutesToPurchase()
    {
//        return 15;
        $minutes = FunctionHelper::getValueSystemConfigurationByKey('max_minutes_to_buy') ?? 5;
        return intval($minutes) + 1;
//        return intval($minutes) + 2;
    }
}

