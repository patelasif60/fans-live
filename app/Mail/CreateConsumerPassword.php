<?php


namespace App\Mail;


use Illuminate\Mail\Mailable;

class CreateConsumerPassword extends Mailable
{
	/**
	 * The user instance.
	 *
	 * @var user
	 */
	public $user;

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
	public function __construct($user)
	{
		$this->user = $user;
		$this->logo = asset(config('mail.mail_config.logo'));
		$this->subject = 'Welcome to ' . config('mail.mail_config.subject_title') . ' - set up your password';
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('emails.create_consumer_password');
	}
}
