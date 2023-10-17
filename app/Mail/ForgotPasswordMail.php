<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
use App\Helpers\MailHelper;
use Crypt;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user     = $user;
        $this->settings = Setting::find(1);
        $this->token    = Crypt::encryptString($user->token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.forget_password')
            ->subject(config('app.name') . ' - Reset your password')
            ->from($this->settings->contact_email, config('app.name') . ' Support Team')
            ->with(['user' => $this->user, 'token' => $this->token,]);
    }
}
