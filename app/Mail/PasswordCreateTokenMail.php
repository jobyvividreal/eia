<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
use App\Helpers\MailHelper;
use Crypt;

class PasswordCreateTokenMail extends Mailable
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
        $this->token    = Crypt::encryptString($user->password_create_token);
        $this->settings = Setting::find(1);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {       
        return $this->markdown('email.password-create-token-mail')
            ->subject(config('app.name') . ' - User Create Password')
            ->from($this->settings->contact_email, config('app.name') . ' Support Team')
            ->with(['user' => $this->user, 'token' => $this->token,]);
    }
}
