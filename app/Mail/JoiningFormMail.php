<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class JoiningFormMail extends Mailable
{

    public $details;
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
        return $this->from("recruitment@vnrseeds.com", "VNR Recruitment")->subject($this->details['subject'])->markdown('emails.JoiningFormMail')->with("details", $this->details);
    }
}
