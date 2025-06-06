<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action('\App\Http\Controllers\Restaurant\TicketController@update', [$table->id]),
            'method' => 'PUT',
            'id' => 'table_edit_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('restaurant.edit_table')</h4>
        </div>

        <div class="modal-body">

            <div class="form-group">
                {!! Form::label('number_of_day', __('ticket.number_of_day') . ':*') !!}
                {!! Form::text('number_of_day', $table->number_of_day, ['class' => 'form-control', 'required', 'placeholder' => __('ticket.number_of_day')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('product_count', __('ticket.product_count') . ':*') !!}
                {!! Form::text('product_count',$table->product_count, ['class' => 'form-control', 'required', 'placeholder' => __('ticket.product_count')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('price', __('ticket.product_count') . ':*') !!}
                {!! Form::text('price',$table->price, ['class' => 'form-control', 'required', 'placeholder' => __('ticket.price')]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('ticket.status') . ':*') !!}
                {!! Form::select('status', $status, $table->status, [
                    'class' => 'form-control select2',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>
        </div>




        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
