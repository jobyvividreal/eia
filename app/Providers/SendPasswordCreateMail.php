<?php

namespace App\Providers;

use App\Providers\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordCreateMail
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
     * @param  \App\Providers\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        //
    }
}
