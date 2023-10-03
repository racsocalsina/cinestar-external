<?php


namespace App\Jobs;


use App\Mail\EmailNewContactUsRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function handle()
    {
        Mail::send(new EmailNewContactUsRecord($this->contact));
    }
}
