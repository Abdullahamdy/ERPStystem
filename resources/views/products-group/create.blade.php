<style>
    .disabled-look {
        background-color: #e9ecef;
        opacity: 1;
        pointer-events: none;
    }
</style>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('\App\Http\Controllers\Restaurant\ProductsGroupController@store'),
            'method' => 'post',
            'id' => 'table_add_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">@lang('messages.add_product_group')</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('groups.group_name') . ':*') !!}
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('groups.group_name')
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description', __('groups.group_description') . ':') !!}
                {!! Form::textarea('description', null, [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => __('groups.group_description')
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('products', __('groups.select_products') . ':*') !!}
                {!! Form::select(
                    'products[]',
                    $products,
                    null,
                    [
                        'class' => 'form-control select2',
                        'multiple' => 'multiple',
                        'required',
                        'data-placeholder' =>  __('groups.select_products') 
                    ]
                ) !!}
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('#group_add_form').validate({
            rules: {
                name: {
                    required: true
                },
                'products[]': {
                    required: true
                }
            },
            messages: {
                name: {
                    required: LANG.required_field
                },
                'products[]': {
                    required: LANG.required_field
                }
            }
        });
    });
</script>