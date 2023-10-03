<?php


namespace App\Jobs;

use App\Services\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class SendVerificationMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone_number;
    protected $code;

    /**
     * Create a new job instance.
     * @param $phone_number
     * @param $code
     * @return void
     */
    public function __construct($phone_number, $code)
    {
        $this->phone_number = $phone_number;
        $this->code = $code;
    }

    /**
     * Execute the job.
     * @param TwilioService $twilioService
     * @throws Exception
     */
    public function handle(TwilioService $twilioService)
    {
        $twilioService->sendMessage("+51$this->phone_number",
            "Tu código para restablecer tu contraseña en Cinestar es: ".$this->code);
    }
}

