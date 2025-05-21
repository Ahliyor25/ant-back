<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;


    public $body;
    /**
     * Create a new message instance.
     */
    public function __construct($body)
    {
        //
        $this->body = $body;
    }

    /**
     * Get the message envelope.
     */


    /**
     * Get the message content definition.
     */


    /**
     * Get the attachments for the message.
     *
     * @return OrderMail
     */
    public function build()
    {
        return $this->markdown('emails.OrderMail')->with('body',$this->body);
    }
}
