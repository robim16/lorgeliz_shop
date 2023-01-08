<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientePrivateMail extends Mailable
{
    use Queueable, SerializesModels;

    private $cliente;
    private $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente, $message)
    {
        $this->cliente = $cliente;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("mensaje para :cliente", ['cliente' => $this->cliente])
            ->markdown('emails.clientePrivateMail')
            ->with('message', $this->message);
    }
}
