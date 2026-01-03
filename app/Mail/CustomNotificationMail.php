<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomNotificationMail extends Mailable
{
    use SerializesModels;

    public $subjectText;
    public $greetingText;
    public $messageText;
    public $footerText;

    public function __construct($subject, $greeting, $message, $footer)
    {
        $this->subjectText = $subject;
        $this->greetingText = $greeting;
        $this->messageText = $message;
        $this->footerText = $footer;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view('emails.custom-notification')
            ->with([
                'subject' => $this->subjectText,
                'greeting' => $this->greetingText,
                'messageText' => $this->messageText,
                'footerText' => $this->footerText,
                'logoPath' => 'https://admin.vacayguider.com/assets/images/vacayguider.png',
            ]);
    }
}
