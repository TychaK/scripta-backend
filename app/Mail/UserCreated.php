<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $user;

    public function __construct($user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.verification-mail')->with([
            'user' => $this->user,
            'link' => env('APP_URL') . '/verify/' . $this->user->verification_hash
        ])
            ->subject(env('APP_NAME') . ' Account Verification')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->to(env('MAIL_FROM_ADDRESS'))
            ->replyTo(env('MAIL_FROM_ADDRESS'));
    }
}
