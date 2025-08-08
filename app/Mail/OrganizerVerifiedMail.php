<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizerVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $organizer;
    /**
     * Create a new message instance.
     */
    public function __construct($organizer)
    {
        $this->organizer = $organizer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Organizer Account is Verified')
                    ->view('emails.organizer-verified')
                    ->with('organizer', $this->organizer);
    }
}
