<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMatchTicketPDF extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The logo instance.
     *
     * @var logo
     */
    public $logo;

    /**
     * The subject instance.
     *
     * @var subject
     */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketsPdf)
    {
        // dd('here');
        $this->ticketsPdf = $ticketsPdf;
        $this->logo = asset(config('mail.mail_config.logo'));
        $this->subject = 'Fanslive - match tickets';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_match_ticket_pdf')->attach($this->ticketsPdf, [
                            'as' => 'ticket_detail.pdf',
                            'mime' => 'application/pdf',
                    ]);
    }
}
