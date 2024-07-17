<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Club;

class HospitalitySuiteTransactionEmail extends Mailable
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
        $hospitalitySuiteTransaction = $this->emailDetails;
        $clubDetail = Club::find($hospitalitySuiteTransaction->club_id);
        return $this
                ->subject(__($this->subject))
                ->view('emails.hospitalitysuite_transaction_email', compact('hospitalitySuiteTransaction','clubDetail'));
    }
}
