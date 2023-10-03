<?php


namespace App\Jobs;


use App\Mail\EmailWhenClaimIsCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendClaimEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;
    private $claim;

    public function __construct($path, $claim)
    {
        $this->path = $path;
        $this->claim = $claim;
    }

    public function handle()
    {
        Mail::send(new EmailWhenClaimIsCreated($this->path, $this->claim));
    }
}
