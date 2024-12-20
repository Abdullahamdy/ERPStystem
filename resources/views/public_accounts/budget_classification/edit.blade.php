

<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('BudgetClassificationController@update', $budget_classification->id),
            'method' => 'PUT',
            'id' => 'table_edit_form',
        ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">@lang('incomeclassifications.edit_income_classification')</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('incomeclassifications.name') . ':*') !!}
                {!! Form::text('name', $budget_classification->name, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('incomeclassifications.name'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('transaction_side', __('incomeclassifications.transaction_side') . ':*') !!}
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div>
                        {!! Form::radio('transaction_side', 'credit', $budget_classification->transaction_side == 'credit', [
                            'id' => 'transaction_side_credit',
                        ]) !!}
                        {!! Form::label('transaction_side_credit', __('incomeclassifications.credit')) !!}
                    </div>
                    <div>
                        {!! Form::radio('transaction_side', 'debit', $budget_classification->transaction_side == 'debit', [
                            'id' => 'transaction_side_debit',
                        ]) !!}
                        {!! Form::label('transaction_side_debit', __('incomeclassifications.debit')) !!}
                    </div>
                </div>
            </div>
            

            <div class="form-group">
                {!! Form::label('type', __('incomeclassifications.type') . ':*') !!}
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div>
                        {!! Form::radio('type', 'detailed', $budget_classification->type == 'detailed', [
                            'id' => 'type_detailed',
                        ]) !!}
                        {!! Form::label('type_detailed', __('incomeclassifications.detailed')) !!}
                    </div>
                    <div>
                        {!! Form::radio('type', 'result', $budget_classification->type == 'result', [
                            'id' => 'type_result',
                        ]) !!}
                        {!! Form::label('type_result', __('incomeclassifications.result')) !!}
                    </div>
                    <div>
                        {!! Form::radio('type', 'title', $budget_classification->type == 'title', [
                            'id' => 'type_title',
                        ]) !!}
                        {!! Form::label('type_title', __('incomeclassifications.title')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div>
                    {!! Form::checkbox('net_profit_loss', 1, $budget_classification->net_profit_loss ?? false, ['id' => 'is_profit_loss']) !!}
                    {!! Form::label('net_profit_loss', __('budgetclassification.net_profit_loss')) !!}
                </div>
            </div>
            
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>
