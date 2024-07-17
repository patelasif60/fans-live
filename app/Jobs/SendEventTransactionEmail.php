<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\EventTransactionEmail;
use Illuminate\Support\Facades\Mail;

class SendEventTransactionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Object variable.
     *
     * @return void
     */
    public $eventTransaction;

     /**
     * Object variable.
     *
     * @return void
     */
    public $consumer;

     /**
     *  variable.
     *
     * @return void
     */
    public $clubAdmins;

     /**
     * variable.
     *
     * @return void
     */
    public $superAdmins;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eventTransaction,$consumer,$clubAdmins,$superAdmins)
    {
         $this->eventTransaction = $eventTransaction;
         $this->consumer = $consumer;
         $this->clubAdmins = $clubAdmins;
         $this->superAdmins = $superAdmins;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = 'messages.transaction_emails.event_transaction_email';
        if($this->clubAdmins)
        {
            $subject = 'messages.transaction_emails.event_transaction_email';
            foreach ($this->clubAdmins as $key => $value)
            {
                 Mail::to($value)->send(new EventTransactionEmail($this->eventTransaction, $subject));
            }
        }
        if($this->superAdmins)
        {
            $subject = 'messages.transaction_emails.event_transaction_email';
            foreach ($this->superAdmins as $key => $value)
            {
                 Mail::to($value)->send(new EventTransactionEmail($this->eventTransaction, $subject));
            }
        }
        Mail::to($this->consumer->user->email)->send(new EventTransactionEmail($this->eventTransaction, $subject));

    }
}
