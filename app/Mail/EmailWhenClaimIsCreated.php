<?php


namespace App\Mail;


use App\Helpers\FunctionHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWhenClaimIsCreated extends Mailable
{
    use Queueable, SerializesModels;

    private $path;
    private $claim;

    public function __construct($path, $claim)
    {
        $this->path = $path;
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS"), ucfirst(strtolower($this->claim->trade_name)))
            ->subject('Libro de reclamaciones')
            ->to(FunctionHelper::getSupportEmailByTradeName($this->claim->trade_name))
            ->with(['claim' => $this->claim])
            ->markdown('emails.claim')
            ->attach(storage_path('app/'.$this->path), [
                'as'   => 'Documento-de-sustento.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
