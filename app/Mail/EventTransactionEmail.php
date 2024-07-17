<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Club;

class EventTransactionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailDetails,$subject)
    {
        $this->emailDetails = $emailDetails;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $eventTransaction = $this->emailDetails;
        $clubDetail = Club::find($eventTransaction->club_id);
        return $this
                ->subject(__($this->subject))
                ->view('emails.event_transaction_email', compact('eventTransaction','clubDetail'));
    }
}
