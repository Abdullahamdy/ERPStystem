<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('\App\Http\Controllers\Store\HeadsStoresController@store'),
            'method' => 'post',
            'id' => 'table_add_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('restaurant.add_table')</h4>
        </div>

        <div class="modal-body">

            @if (count($business_locations) == 1)
                @php
                    $default_location = current(array_keys($business_locations->toArray()));
                @endphp
            @else
                @php $default_location = null; @endphp
            @endif


            

            <div class="form-group">
                {!! Form::label('branch_id', __('store.branch') . ':*') !!}
                {!! Form::select('branch_id', $business_locations, $default_location, [
                    'class' => 'form-control select2',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('store_type', __('store.store_type') . ':*') !!}
                {!! Form::select('store_type', $store_type,[], [
                    'class' => 'form-control select2',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('type_cost', __('store.type_cost') . ':*') !!}
                {!! Form::select('type_cost', $cost_type, [], [
                    'class' => 'form-control select2',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('name_ar', __('store.name_ar') . ':*') !!}
                {!! Form::text('name_ar', null, ['class' => 'form-control', 'required', 'placeholder' => __('store.name_ar')]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('name_en', __('store.name_en') . ':*') !!}
                {!! Form::text('name_en', null, ['class' => 'form-control', 'required', 'placeholder' => __('store.name_en')]) !!}
            </div>
         

            <div class="form-group">
                {!! Form::label('description', __('store.description') . ':') !!}
                {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => __('store.description')]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('store.status') . ':*') !!}
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
