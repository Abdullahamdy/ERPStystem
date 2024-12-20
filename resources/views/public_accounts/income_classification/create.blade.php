<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => action('IncomeClassificationController@store'), 'method' => 'post',  'id' => 'table_add_form', ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('store.add_cost_center')</h4>
        </div>

        <div class="modal-body">
         
            <div class="form-group">
                {!! Form::label('name', __('incomeclassifications.name') . ':*') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('incomeclassifications.name')]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('transaction_side', __('incomeclassifications.transaction_side') . ':*') !!}
                {!! Form::select('transaction_side', ['credit' => __('incomeclassifications.credit'), 'detailed' => __('incomeclassifications.debit')], null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('type', __('incomeclassifications.type') . ':*') !!}
                {!! Form::select('type', ['detailed' => __('incomeclassifications.detailed'), 'result ' => __('incomeclassifications.result '),'title' => __('incomeclassifications.title')], null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')]); !!}
            </div>

           
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>