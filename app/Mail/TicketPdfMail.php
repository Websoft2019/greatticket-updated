<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class TicketPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseDetails;
    public $logoCid;

    protected $pdfPath;

    public function __construct($purchaseDetails, $pdfPath)
    {
        $this->purchaseDetails = $purchaseDetails;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        // Embed the logo and get the CID
        // $logoCid = $this->embed(public_path('site/images/logo.png'));
        $companylogo = public_path('site/images/logo.png');

        $this->view('emails.ticket-purchase')
            ->with([
                'purchaseDetails' => $this->purchaseDetails,
                'customer_name' => $this->purchaseDetails['customer_name'],
                'data' => $this->purchaseDetails['data'],
                'total_tickets' => $this->purchaseDetails['total_tickets'],
                'total_price' => $this->purchaseDetails['total_price'],
            ])
            ->subject('Ticket Purchase Details')
            ->attach($this->pdfPath, [
                'as' => 'YourTickets.pdf',
                'mime' => 'application/pdf',
            ]);
            
    }
}
