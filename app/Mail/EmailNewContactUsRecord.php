<?php


namespace App\Mail;


use App\Helpers\FunctionHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNewContactUsRecord  extends Mailable
{
    use Queueable, SerializesModels;

    private $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS"), ucfirst(strtolower($this->contact->trade_name)))
            ->subject('Contáctanos')
            ->to(FunctionHelper::getSupportEmailByTradeName($this->contact->trade_name))
            ->with(['contact' => $this->contact])
            ->markdown('emails.contact-us');
    }
}
