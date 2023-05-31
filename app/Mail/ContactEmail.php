<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Lib\EmailTemplate;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    public $subject;
    protected $body;
    protected $template;
    protected $locationName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailTemplate $emailTemplate)
    {
        $this->name = $emailTemplate->name;
        $this->subject = $emailTemplate->subject;
        $this->body = $emailTemplate->body;
        $this->locationName = $emailTemplate->location_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.contact-email',
        [
            'name' => $this->name,
            'body' => $this->body,
            'location_name' => $this->locationName
        ])
        ->subject($this->subject);
    }
}
