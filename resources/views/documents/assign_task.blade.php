<div id="data-create-modal" class="modal">
  {!! Form::open(['class'=>'ajax-submit', 'id'=> 'assignTaskForm']) !!}
    <div class="modal-content">
      <a class="btn-floating mb-1 waves-effect waves-light right modal-close"><i class="material-icons">clear</i></a>
      <div class="modal-header"><h4 class="modal-title">Assign Task Form</h4> </div>
      {{ csrf_field() }}
      {!! Form::hidden('document_id', $document->id, ['id' => 'documentId'] ); !!}
      {!! Form::hidden('assignedId', $assignedId ?? '', ['id' => 'assignedId'] ); !!}
      {!! Form::hidden('assignRoute', url('task-assign'), ['id' => 'assignRoute'] ); !!}
      <div class="card-body" id="">
        <div class="row">
          <div class="input-field col s12">
            {!! Form::select('assigned_to', $variants->users, '', ['id' => 'assigned_to', 'class' => 'select2 browser-default task-store-input', 'placeholder'=>'Please select User']) !!}
            <!-- <label for="assigned_to" class="label-placeholder active"> Please select User <span class="red-text">*</span> </label> -->
          </div>
          <div class="input-field col s12">
            {!! Form::textarea('details', '', ['id' => 'details', 'class' => 'materialize-textarea task-store-input', 'placeholder' => 'Task Details']) !!}
          </div>       
          @can('documents-task-complete')   
            <div class="input-field col s12 task-complete-checkbox" style="display:none;">
              <p class="mb-1"><label><input type="checkbox" id="taskCompleted" name="taskCompleted"> <span>Task Completed</span></label></p>
              <p><label>Check to complete the task</label></p>
            </div>
            <div class="input-field col s12 task-completed-section" style="display:none;">
              {!! Form::textarea('completedNote', '', ['id' => 'completedNote', 'class' => 'materialize-textarea', 'placeholder' => 'Please enter note']) !!}
            </div>
          @endcan
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn waves-effect waves-light modal-action modal-close" type="reset" id="resetForm">Close</button>
      <button class="btn cyan waves-effect waves-light store-task" type="submit" name="action" id="formSubmitBtn">Submit <i class="material-icons right">send</i></button>
      <!-- <button class="btn cyan waves-effect waves-light update-task" type="button" name="action" id="taskCompleteBtn">Complete Task <i class="material-icons right">send</i></button> -->
    </div>
  {{ Form::close() }}
</div>