<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Mail\SendTaskAssignedEmail as SendTaskAssignedEmailForm;
use Mail;

class SendTaskAssignedEmail
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskAssigned  $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        $task               = $event->task;
        $user               = User::find($task->assigned_to);
        $email              = new SendTaskAssignedEmailForm($task);
        Mail::to($user->email)->send($email);

    }
}
