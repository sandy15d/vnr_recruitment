<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MrfStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from("recruitment@vnrseeds.com", "VNR Recruitment")->subject($this->details['subject'])->markdown('emails.MrfStatusChangeMail')->with("details", $this->details);
    }
}
