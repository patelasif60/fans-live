<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\HospitalitySuiteTransactionEmail;
use Illuminate\Support\Facades\Mail;

class SendHospitalitySuiteTransactionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Object variable.
     *
     * @return void
     */
    public $hospitalitySuiteTransaction;

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
    public function __construct($hospitalitySuiteTransaction,$consumer,$clubAdmins,$superAdmins)
    {
         $this->hospitalitySuiteTransaction = $hospitalitySuiteTransaction;
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
       $subject = 'messages.transaction_emails.hospitalitysuite_transaction_email';
       if($this->clubAdmins)
        {
            $subject = 'messages.transaction_emails.hospitalitysuite_transaction_email';
            foreach ($this->clubAdmins as $key => $value)
            {
                 Mail::to($value)->send(new HospitalitySuiteTransactionEmail($this->hospitalitySuiteTransaction,$subject));
            }
        }
        if($this->superAdmins)
        {
            $subject = 'messages.transaction_emails.hospitalitysuite_transaction_email';
            foreach ($this->superAdmins as $key => $value)
            {
                 Mail::to($value)->send(new HospitalitySuiteTransactionEmail($this->hospitalitySuiteTransaction,$subject));
            }
        }
        Mail::to($this->consumer->user->email)->send(new HospitalitySuiteTransactionEmail($this->hospitalitySuiteTransaction,$subject));
    }
}
