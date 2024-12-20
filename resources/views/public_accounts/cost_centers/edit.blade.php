<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('CostCentersController@update', $cost_center->id),
            'method' => 'PUT',
            'id' => 'table_edit_form',
        ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('store.edit_cost_center')</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name_ar', __('public_account.name_ar') . ':*') !!}
                {!! Form::text('name_ar', $cost_center->name_ar, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('public_account.name_ar'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('name_en', __('public_account.name_en') . ':*') !!}
                {!! Form::text('name_en', $cost_center->name_en, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('public_account.name_en'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('type', __('public_account.type') . ':*') !!}
                {!! Form::select(
                    'type',
                    ['main' => __('public_account.main'), 'detailed' => __('public_account.detailed')],
                    $cost_center->type,
                    ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')],
                ) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('public_account.status') . ':*') !!}
                {!! Form::select(
                    'status',
                    ['active' => __('public_account.active'), 'inactive' => __('public_account.inactive')],
                    $cost_center->status,
                    ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')],
                ) !!}
            </div>

            
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>
