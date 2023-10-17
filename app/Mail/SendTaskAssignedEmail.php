<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
use App\Models\User;

class SendTaskAssignedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $settings;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task         = $task;
        $this->settings     = Setting::find(1);
        $this->user         = User::find($task->assigned_to);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.user.task_assigned')
        ->subject('New task assigned')
        ->from($this->settings->contact_email, config('app.name') . ' Support Team')
        ->with(['task' => $this->task, 'user' => $this->user]);
    }
}
