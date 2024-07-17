<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The token instance.
     *
     * @var token
     */
    public $token;

    /**
     * The logo instance.
     *
     * @var logo
     */
    public $logo;

    /**
     * The email instance.
     *
     * @var email
     */
    public $email;

    /**
     * The subject instance.
     *
     * @var subject
     */
    public $subject;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
        $this->logo = asset(config('mail.mail_config.logo'));
        $this->subject = config('mail.mail_config.subject_title').' - reset password';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reset')->subject($this->subject);
    }
}
