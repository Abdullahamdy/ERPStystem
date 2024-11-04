<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('\App\Http\Controllers\Restaurant\TicketController@store'),
            'method' => 'post',
            'id' => 'table_add_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('restaurant.add_table')</h4>
        </div>

        <div class="modal-body">

          

            <div class="form-group">
                {!! Form::label('number_of_day', __('ticket.number_of_day') . ':*') !!}
                {!! Form::text('number_of_day', null, ['class' => 'form-control', 'required', 'placeholder' => __('ticket.number_of_day')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('product_count', __('ticket.product_count') . ':*') !!}
                {!! Form::text('product_count', null, ['class' => 'form-control', 'required', 'placeholder' => __('ticket.product_count')]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('ticket.status') . ':*') !!}
                {!! Form::select('status', $status, [], [
                    'class' => 'form-control select2',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>
        </div>


        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>



        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
