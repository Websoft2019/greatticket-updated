<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MakeReservedPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($purchaseDetails)
    {
        $this->purchaseDetails = $purchaseDetails;
    }

    public function build()
    {

        $this->view('emails.make-reserved-payment')
            ->with([
                'purchaseDetails' => $this->purchaseDetails,
            ])
            ->subject('Payment Request for Your Reserved Tickets');
    }
}
