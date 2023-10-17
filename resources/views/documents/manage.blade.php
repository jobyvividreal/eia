<div id="proceed-to-document-modal" class="modal">
      <div class="modal-content">
      <a class="btn-floating mb-1 waves-effect waves-light right modal-close"><i class="material-icons">clear</i></a>
          <div class="modal-header"><h4 class="modal-title">Create Document Form</h4> </div>
          {!! Form::open(['class'=>'ajax-submit','id'=> 'proceedDocumentForm']) !!}
          {{ csrf_field() }}
          <div class="card-body" id="">
            <div class="row">
              <div class="input-field col s6">
                {!! Form::select('proceed_project_id', $variants->projects, '', ['id' => 'proceed_project_id', 'class' => 'select2 browser-default', 'placeholder'=>'Please select a project']) !!}
                <label for="name" class="label-placeholder active"> Projects <span class="red-text">*</span></label>
              </div>
              <div class="input-field col s6">
                {!! Form::select('proceed_to_eia_id', [], '', ['id' => 'proceed_to_eia_id', 'class' => 'select2 browser-default', 'placeholder'=>'Please select EIA']) !!}
                <label for="name" class="label-placeholder active"> EIA <span class="red-text">*</span></label>
              </div>
              
            </div>
          </div>
      </div>
      <div class="modal-footer">
          <button class="btn waves-effect waves-light modal-action modal-close" type="reset" id="resetForm">Close</button>
          <button class="btn cyan waves-effect waves-light" type="submit" name="action" id="eiaProceedBtn">Proceed <i class="material-icons right">send</i></button>
      </div>
      {{ Form::close() }}
</div>