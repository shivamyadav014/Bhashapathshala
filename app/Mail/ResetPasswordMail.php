<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        $resetUrl = url('/password/reset/' . $this->token);
        return $this->subject('Reset Your Password')
            ->view('emails.reset_password')
            ->with(['resetUrl' => $resetUrl]);
    }
}
