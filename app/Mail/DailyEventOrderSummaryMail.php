<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class DailyEventOrderSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $organizer;
    public $event;
    public $orders;
    public $reportDate;
    public $summary; 
    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new message instance.
     */
    public function __construct($organizer, $event, $orders, $reportDate)
    {
        $this->organizer = $organizer;
        $this->event = $event;
        $this->orders = $orders;
        $this->reportDate = $reportDate;

    // Calculate totals here
    $totalRevenue = 0;
    $totalPackages = 0;
    foreach ($orders as $order) {
        $totalRevenue += ($order->grandtotal);
        foreach ($order->orderPackages as $orderPackage) {
            if ($orderPackage->package->event_id == $event->id) {
                $totalPackages += $orderPackage->quantity;
            }
        }
    }

    $this->summary = [
        'totalRevenue' => $totalRevenue,
        'totalPackages' => $totalPackages,
        'averageOrderValue' => count($orders) > 0 ? $totalRevenue / count($orders) : 0,
    ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Order Report - ' . $this->event->title . ' (' . now()->subDay()->format('Y-m-d') . ')',
            from: config('mail.from.address'),
            replyTo: [
                config('mail.from.address'),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
{
    return new Content(
        view: 'emails.daily-event-order-report',
        with: [
            'organizer' => $this->organizer,
            'event' => $this->event,
            'orders' => $this->orders,
            'reportDate' => $this->reportDate,
            'summary' => $this->summary,
        ],
    );
}

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            // Uncomment to attach CSV report
            // Attachment::fromData(function () {
            //     return $this->generateCSV();
            // }, 'daily-orders-' . str_replace(' ', '-', $this->event->title) . '.csv')
            //     ->withMime('text/csv'),
        ];
    }

    /**
     * Generate CSV data for attachment (optional)
     */
    private function generateCSV(): string
    {
        $csv = "Order ID,Customer Name,Customer Email,Package Name,Quantity,Price,Total,Status,Order Date\n";
        
        foreach ($this->orders as $order) {
            foreach ($order->orderPackages as $orderPackage) {
                if ($orderPackage->package->event_id == $this->event->id) {
                    $csv .= implode(',', [
                        $order->id,
                        '"' . ($order->user->name ?? 'Guest') . '"',
                        '"' . ($order->user->email ?? 'N/A') . '"',
                        '"' . $orderPackage->package->name . '"',
                        $orderPackage->quantity,
                        $orderPackage->price,
                        $orderPackage->price * $orderPackage->quantity,
                        $order->status ?? 'pending',
                        $order->created_at->format('Y-m-d H:i:s')
                    ]) . "\n";
                }
            }
        }
        
        return $csv;
    }
}