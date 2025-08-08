<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TicketPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseDetails, $qrCodeUrls;

    /**
     * Create a new message instance.
     */
    public function __construct($purchaseDetails)
    {
        $this->purchaseDetails = $purchaseDetails;
        // Prepare the QR code attachments
        $this->prepareQrCodes();
    }

    /**
     * Prepare QR code attachments.
     */
    private function prepareQrCodes()
    {
        foreach ($this->purchaseDetails['data'] as $packageDetails) {
            foreach ($packageDetails['ticket_users'] as $ticketUser) {
                // $qrCodePath = storage_path('app/public/' . $ticketUser['qr_image']);
                $qrCodePath = public_path('storage/' . $ticketUser['qr_image']);
                // Store the path for later attachment
                // Check if file exists before adding to the list
                if (file_exists($qrCodePath)) {
                    $this->qrCodeUrls[$ticketUser['name']] = $qrCodePath;
                } else {
                    // Handle missing file (log it, use a placeholder, etc.)
                    \Log::warning("QR code image for " . $ticketUser['name'] . " not found: " . $qrCodePath);
                }
            }
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Ticket Purchase Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new Content(
            view: 'emails.ticket-purchase',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Loop through ticket users and attach QR code for each user
        foreach ($this->purchaseDetails['data'] as $packageDetails) {
            foreach ($packageDetails['ticket_users'] as $ticketUser) {
                $qrCodePath = $this->qrCodeUrls[$ticketUser['name']];

                // Create attachment from the path with proper file name and mime type
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($qrCodePath)
                    ->as($ticketUser['name'] . '_ticket_qr.png')  // Custom name for the file
                    ->withMime('image/png');  // MIME type for the QR code image
            }
        }

        return $attachments;
    }

    public function build()
    {
        return $this->view('emails.ticket-purchase')
            ->with([
                'customer_name' => $this->purchaseDetails['customer_name'],
                'data' => $this->purchaseDetails['data'],
                'total_tickets' => $this->purchaseDetails['total_tickets'],
                'total_price' => $this->purchaseDetails['total_price'],
                'service_charge' => $this->purchaseDetails['service_charge'],
            ]);
    }
}
