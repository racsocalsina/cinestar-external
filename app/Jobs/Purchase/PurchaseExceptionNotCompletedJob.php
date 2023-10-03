<?php


namespace App\Jobs\Purchase;


use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchasePaymentRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurchaseExceptionNotCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Purchase $purchase;
    private $purchaseRepository;
    private $purchasePaymentRepository;

    public function __construct(Purchase $purchase, PurchaseRepositoryInterface $purchaseRepository, PurchasePaymentRepositoryInterface $purchasePaymentRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->purchasePaymentRepository = $purchasePaymentRepository;
        $this->purchase = $purchase;
    }

    public function handle()
    {
        $this->purchasePaymentRepository->updatePurchaseAsConfirmed($this->purchase);

        $subject = '[URGENTE] Compra ' . $this->purchase->id . ' no completada';

        $exception = new \Exception();
        $exceptionDto = (new BuildExceptionDto($exception))->build();
        $exceptionDto->setSubject($subject);
        $exceptionDto->setMessage('La compra con el id '. $this->purchase->id . ' a sido procesado por la pasarela de pago, pero no completo el flujo, por favor ejecute el proceso manualmente para completar.');
//        SendErrorEmail::dispatch($exceptionDto);
        FunctionHelper::sendErrorMail($exceptionDto);
    }
}
