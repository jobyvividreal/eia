<?php

namespace App\Http\Controllers;

use App\Models\TaskAssign;
use App\Events\TaskAssigned;
// use App\Jobs\SendTaskAssignedEmailJob;
use Notification;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use App\Models\Document;
use Event;
use Carbon;


class TaskAssignController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task                           = TaskAssign::create($request->only(['document_id', 'assigned_to', 'details']));
        $task->document->is_assigned    = 1;
        $task->document->save();

        //Send email to Assigned User
        // dispatch(new SendTaskAssignedEmailJob($task));

        // Notification::send($task->assignedTo, new TaskAssignedNotification($task));

        $task->assignedTo->notify(new TaskAssignedNotification($task));

        //Event will store data in Notifications Table
        event(new TaskAssigned($task));

        return ['flagError' => false, 'message' => "Task assigned successfully"];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TaskAssign $taskAssign)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TaskAssign $taskAssign)
    {
        if ($request->ajax()) {
            return ['flagError' => false, 'data' => $taskAssign];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskAssign $taskAssign)
    {
        if ($request->has('taskCompleted') ) {
            $completeTask = $this->completeTask($taskAssign, $request);
        } else {
            $task                   = TaskAssign::create($request->only(['document_id', 'assigned_to', 'details']));
            $taskAssign->status     = 2 ;
        }
        $action                     =  ($request->has('taskCompleted')) ? 'completed' : 'reassigned';
        return ['flagError' => false, 'message' => 'Task ' . $action . ' successfully'];
    }

    public function completeTask($taskAssign, $request) 
    {
        $taskAssign->status         = 3 ;
        $taskAssign->completed_note = $request['completedNote'];
        $taskAssign->completed_by   = auth()->user()->id;
        $taskAssign->completed_at   = Carbon\Carbon::now();
        $taskAssign->save();
        $document                   = Document::where('id', $request['document_id'])->update(['is_assigned' => 0]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskAssign  $taskAssign
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskAssign $taskAssign)
    {
        //
    }
}