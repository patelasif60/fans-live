<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Club;

class LoyaltyRewardTransactionEmail extends Mailable
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
        $loyaltyRewardTransaction = $this->emailDetails;
        $clubDetail = Club::find($loyaltyRewardTransaction->club_id);
        return $this
                ->subject(__($this->subject))
                ->view('emails.loyaltyreward_transaction_email', compact('loyaltyRewardTransaction','clubDetail'));
    }
}
