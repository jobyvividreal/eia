<div id="data-create-modal" class="modal">
    {!! Form::open(['class'=>'ajax-submit', 'id'=> Str::camel($page->title).'Form']) !!}
      <div class="modal-content">
      <a class="btn-floating mb-1 waves-effect waves-light right modal-close"><i class="material-icons">clear</i></a>
          <div class="modal-header"><h4 class="modal-title">{{Str::singular($page->title)}} Form</h4> </div>
          {{ csrf_field() }}
          {!! Form::hidden('data_id', '' , ['id' => 'data_id'] ); !!}
          {!! Form::hidden('pageTitle', Str::camel($page->title), ['id' => 'pageTitle'] ); !!} 
          {!! Form::hidden('pageRoute', url($page->route), ['id' => 'pageRoute'] ); !!}
          <div class="card-body" id="">
              <div class="row">
                <div class="input-field col s12">
                  {!! Form::text('name', '', ['id' => 'name']) !!}
                  <label for="name" class="label-placeholder active">Company name<span class="red-text">*</span></label>
                </div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <button class="btn waves-effect waves-light modal-action modal-close" type="reset" id="resetForm">Close</button>
          <button class="btn cyan waves-effect waves-light" type="submit" name="action" id="additional-submit-btn">Submit <i class="material-icons right">send</i></button>
      </div>
    {{ Form::close() }}
</div>