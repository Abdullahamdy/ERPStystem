<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => action('CostCentersController@store'), 'method' => 'post',  'id' => 'table_add_form', ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('store.add_cost_center')</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name_ar', __('public_account.name_ar') . ':*') !!}
                {!! Form::text('name_ar', null, ['class' => 'form-control', 'required', 'placeholder' => __('public_account.name_ar')]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('name_en', __('public_account.name_en') . ':*') !!}
                {!! Form::text('name_en', null, ['class' => 'form-control', 'required', 'placeholder' => __('public_account.name_en')]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('type', __('public_account.type') . ':*') !!}
                {!! Form::select('type', ['main' => __('public_account.main'), 'detailed' => __('public_account.detailed')], null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('public_account.status') . ':*') !!}
                {!! Form::select('status', ['active' => __('public_account.active'), 'inactive' => __('public_account.inactive')], null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.please_select')]); !!}
            </div>

           
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>