<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewMailOnline extends Mailable
{
    public $details;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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

        return $this->from("recruitment@vnrseeds.com", "VNR Recruitment")->subject($this->details['subject'])->markdown('emails.InterviewMailOnline')->with("details", $this->details);
    }
}
