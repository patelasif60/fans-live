<?php

namespace App\Jobs;

use App\Mail\ProductTransactionEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProductTransactionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Object variable.
     *
     * @return void
     */
    public $productTransaction;

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
    public function __construct($productTransaction,$consumer,$clubAdmins,$superAdmins)
    {
         $this->productTransaction = $productTransaction;
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
        $subject = 'messages.transaction_emails.product_transaction_email';
        if($this->clubAdmins)
        {
            $subject = 'messages.transaction_emails.product_transaction_email';
            foreach ($this->clubAdmins as $key => $value)
            {
                 Mail::to($value)->send(new ProductTransactionEmail($this->productTransaction, $subject));
            }
        }
        if($this->superAdmins)
        {
            $subject = 'messages.transaction_emails.product_transaction_email';
            foreach ($this->superAdmins as $key => $value)
            {
                 Mail::to($value)->send(new ProductTransactionEmail($this->productTransaction, $subject));
            }
        }
        Mail::to($this->consumer->user->email)->send(new ProductTransactionEmail($this->productTransaction, $subject));

    }
}
