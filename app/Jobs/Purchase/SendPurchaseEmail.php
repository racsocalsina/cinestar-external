<?php


namespace App\Jobs\Purchase;


use App\Helpers\FunctionHelper;
use App\Mail\EmailPurchaseVoucher;
use App\Models\Purchases\Purchase;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Events\SendPurchaseEmailCompleted;
use App\Enums\PurchaseStatus;

class SendPurchaseEmail  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Purchase $purchase;
    private $config;

    public function __construct(Purchase $purchase, $config = null)
    {
        $this->purchase = $purchase;
        $this->config = $config;
    }

    public function handle()
    {
        $paymentGatewayInfo = $this->purchase->payment_gateway_info;

        if($paymentGatewayInfo && $paymentGatewayInfo->email)
        {
            try {
                Mail::send(new EmailPurchaseVoucher($this->purchase, $this->config));
                event(new SendPurchaseEmailCompleted($this->purchase->id));
            }catch (\Exception $e){
                //throw new \Exception("I am throwing this exception", 1);
                $data = ['error_event_history' => json_encode([PurchaseStatus::ERROR_SEND_EMAIL => 'true'])];
                Purchase::find($this->purchase->id)->update($data);
                $exceptionDto = (new BuildExceptionDto($e))->build();
                $exceptionDto->setSubject('Error en el envio del comprobante de compra');
                FunctionHelper::sendErrorMail($exceptionDto);
            }
        }
        
    }
}
