<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecoverPassword  extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_FROM_ADDRESS"), ucfirst(strtolower($this->data['trade_name'])))
            ->subject('Recuperar Contraseña')
            ->to($this->data['email'])
            ->with(['data' => $this->data])
            ->markdown('emails.recover_password');
    }
}
