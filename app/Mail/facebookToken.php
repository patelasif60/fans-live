<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class facebookToken extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The id instance.
     *
     * @var id
     */
    public $id;

    /**
     * The email instance.
     *
     * @var user
     */
    public $email;
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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $club_slug, $type, $error)
    {
        $this->email = config('mail.mail_config.support_team');
        $this->logo = asset(config('mail.mail_config.logo'));
        $this->id = $id;
        $this->type = $type;
        $this->club_slug = $club_slug;
        $this->error = $error;
        $this->subject = config('mail.mail_config.subject_title').' - Update feeds';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id = $this->id;
        $logo = $this->logo;
        $clubSlug = $this->club_slug;
        $type = $this->type;
        $error = $this->error;

        return $this->view('emails.facebook_token', compact('id', 'clubSlug', 'logo', 'type', 'error'));
    }
}
