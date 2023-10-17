<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.user.welcome_user')
        ->subject('Welcome to '. config('app.name'))
        ->from($this->settings->contact_email, config('app.name') . ' Support Team')
        ->with(['user' => $this->user]);
    }
}
