<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action('Restaurant\TableController@update', [$table->id]), 'method' => 'PUT', 'id' => 'table_edit_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'restaurant.edit_table' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('name', __( 'restaurant.table_name' ) . ':*') !!}
        {!! Form::text('name', $table->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'brand.brand_name' )]) !!}
      </div>

      <div class="form-group">
        {!! Form::label('user_id', __('purchase.user').':*') !!}
        {!! Form::select('user_id', $users, $table->user_id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']) !!}
      </div>

      <div class="form-group">
        {!! Form::label('flower_number', __('purchase.flowersNumber').':*') !!}
        {!! Form::select('flower_number', $followres, $table->flower_number, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']) !!}
      </div>

      <div class="form-group">
        {!! Form::label('description', __( 'restaurant.short_description' ) . ':') !!}
        {!! Form::text('description', $table->description, ['class' => 'form-control', 'placeholder' => __( 'brand.short_description' )]) !!}
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>