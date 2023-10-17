<?php

namespace App\Observers;

use App\Models\TaskAssign;

class TaskAssignObserver
{
    /**
     * Handle the TaskAssign "created" event.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return void
     */
    public function created(TaskAssign $taskAssign)
    {
        $taskAssign->assigned_by = auth()->user()->id;
        $taskAssign->save();
    }

    /**
     * Handle the TaskAssign "updated" event.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return void
     */
    public function updated(TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Handle the TaskAssign "deleted" event.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return void
     */
    public function deleted(TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Handle the TaskAssign "restored" event.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return void
     */
    public function restored(TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Handle the TaskAssign "force deleted" event.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return void
     */
    public function forceDeleted(TaskAssign $taskAssign)
    {
        //
    }
}
