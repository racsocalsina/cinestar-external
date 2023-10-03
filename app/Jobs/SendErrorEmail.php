<?php


namespace App\Jobs;


use App\Services\Mail\Dtos\ExceptionDto;
use App\Services\Mail\MailErrorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendErrorEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dto;

    public function __construct(ExceptionDto $dto)
    {
        $this->dto = $dto;
    }

    public function handle()
    {
        // (new MailErrorService($this->dto))->send();
    }
}
