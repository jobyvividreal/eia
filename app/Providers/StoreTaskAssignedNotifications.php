<?php

namespace App\Providers;

use App\Providers\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Providers\TaskAssigned  $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        //
    }
}
