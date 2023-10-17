<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserLogged;
use App\Listeners\StoreUserLoginHistory;
use App\Events\UserRegistered;
use App\Listeners\SendPasswordCreateMail;
use App\Events\TaskAssigned;
use App\Listeners\SendTaskAssignedEmail;
use App\Listeners\StoreTaskAssignedNotifications;
use App\Observers\TaskAssignObserver;
use App\Models\TaskAssign;
use App\Events\UserCommented;
use App\Listeners\StoreCommentNotification;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserRegistered::class => [
            SendPasswordCreateMail::class,
        ],
        TaskAssigned::class => [
            // SendTaskAssignedEmail::class,
            StoreTaskAssignedNotifications::class, 
            // SendTaskAssignedNotifications::class,
        ],
        UserCommented::class => [
            StoreCommentNotification::class,
        ],
        // UserLogged::class => [
        //     StoreUserLoginHistory::class,
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        TaskAssign::observe(TaskAssignObserver::class);
    }
}
