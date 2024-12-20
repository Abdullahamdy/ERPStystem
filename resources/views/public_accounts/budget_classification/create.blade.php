<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => action('BudgetClassificationController@store'), 'method' => 'post',  'id' => 'table_add_form', ]) !!}
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
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div>
                        {!! Form::radio('transaction_side', 'credit', false, ['id' => 'transaction_side_credit', 'required']) !!}
                        {!! Form::label('transaction_side_credit', __('incomeclassifications.credit')) !!}
                    </div>
                    <div>
                        {!! Form::radio('transaction_side', 'debit', false, ['id' => 'transaction_side_debit']) !!}
                        {!! Form::label('transaction_side_debit', __('incomeclassifications.debit')) !!}
                    </div>
                </div>
            </div>

          
            
            <div class="form-group">
                {!! Form::label('type', __('incomeclassifications.type') . ':*') !!}
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div>
                        {!! Form::radio('type', 'detailed', false, ['id' => 'type_detailed', 'required']) !!}
                        {!! Form::label('type_detailed', __('incomeclassifications.detailed')) !!}
                    </div>
                    <div>
                        {!! Form::radio('type', 'result', false, ['id' => 'type_result']) !!}
                        {!! Form::label('type_result', __('incomeclassifications.result')) !!}
                    </div>
                    <div>
                        {!! Form::radio('type', 'title', false, ['id' => 'type_title']) !!}
                        {!! Form::label('type_title', __('incomeclassifications.title')) !!}
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div>
                    {!! Form::checkbox('net_profit_loss', 1, false, ['id' => 'is_profit_loss']) !!}
                    {!! Form::label('net_profit_loss', __('budgetclassification.net_profit_loss')) !!}
                </div>
            </div>
           
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>