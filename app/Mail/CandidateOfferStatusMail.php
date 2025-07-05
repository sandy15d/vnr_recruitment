<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateOfferStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("recruitment@vnrseeds.com", "VNR Recruitment")->subject($this->details['subject'])->markdown('emails.OfferStatusMail')->with("details", $this->details);
    }
}
