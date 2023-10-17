<?php

namespace App\Listeners;

use App\Events\UserCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification;
use App\Models\User;

class StoreCommentNotification
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
     * @param  \App\Events\UserCommented  $event
     * @return void
     */
    public function handle(UserCommented $event)
    {
        $comment    = $event->comment;
        $users      = User::where('is_admin', '!=', '1')->get();
        foreach($users as $user) {
            $data       = [
                'user_id' => $user->id, 
                'created_by' => $comment->commented_by, 
                'type' => 'user-commented', 
                'title' => 'New comment added', 
                'message' => '<time class="media-meta grey-text darken-2 user-notification"> New comment added by: '.$comment->commentedBy->name.', on '.$comment->created_at->format('M d, Y').'</time>', 
                'icon' => 'stars', 
                'url' =>  url('documents/'.$comment->document_id),
            ]; 
            
            if($user->can('documents-details')) {
                Notification::create($data);
            }
        }
    }
}