<?php


namespace App\Services\Mail;


use App\Helpers\FunctionHelper;
use App\Services\Mail\Dtos\ExceptionDto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailErrorService
{
    private $dto;

    public function __construct(ExceptionDto $dto)
    {
        $this->dto = $dto;
    }

    public function send()
    {
        $e = $this->dto;
        $request = request();
        $url = strtolower($request->url());
        $method = $request->method();

        $date = Carbon::now()->format('d/m/Y h:i:s a');

        // Setup your smtp mailer
        $transport = new \Swift_SmtpTransport(
            env('MAIL_HOST'),
            env('MAIL_PORT'),
            env('MAIL_ENCRYPTION')
        );
        $transport->setUsername(env('MAIL_USERNAME'));
        $transport->setPassword(env('MAIL_PASSWORD'));

        // Any other mailer configuration stuff needed...
        $smtp = new \Swift_Mailer($transport);

        // Set the mailer as smtp
        Mail::setSwiftMailer($smtp);

        // Send message
        $toArray = FunctionHelper::getSystemSupportEmailsArray();

        try{
            Mail::send('emails.error', compact('e', 'date', 'url', 'method'), function ($message) use ($toArray) {
                $message->from(
                    env("MAIL_FROM_ADDRESS"),
                    env("MAIL_FROM_NAME")
                );

                $message->to($toArray)->subject($this->dto->getSubject());
            });
        } catch (\Exception $e) {
            $exception = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($this),
                'exception' => $e->getMessage(),

            ];
            Log::debug("ERROR - EMAIL ", json_encode($exception));
            Log::error($e);
        }
    }
}
