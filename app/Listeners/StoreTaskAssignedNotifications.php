<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification;
use App\Models\User;

class StoreTaskAssignedNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskAssigned  $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        $task                   = $event->task;

        $data                   = [
                                        'user_id' => $task->assigned_to, 
                                        'created_by' => $task->assigned_by, 
                                        'type' => 'task-assigned', 
                                        'title' => 'New Task Assigned', 
                                        'message' => '<time class="media-meta grey-text darken-2 user-notification"> Assigned by: '.$task->assignedBy->name.', on '.$task->created_at->format('M d, Y').'</time>', 
                                        'icon' => 'stars', 
                                        'url' =>  url('documents/'.$task->document_id),
                                ]; 
        $user                   = Notification::create($data);
    }
}