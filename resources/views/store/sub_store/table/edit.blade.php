<style>
    .disabled-look {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }
</style>
<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action('\App\Http\Controllers\Store\SubsStoresController@update', [$table->id]),
            'method' => 'PUT',
            'id' => 'table_edit_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('restaurant.edit_table')</h4>
        </div>

        <div class="modal-body">
            {{-- {!! Form::select('contact_id[]', $customers, $campaign->contact_ids, ['class' => 'form-control select2', 'multiple', 'id' => 'contact_id', 'style' => 'width: 100%;']); !!} --}}

            <div class="form-group">
                {!! Form::label('name_ar', __('store.name_ar') . ':*') !!}
                {!! Form::text('name_ar', $table->name_ar, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('store.name_ar'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('name_en', __('store.name_en') . ':*') !!}
                {!! Form::text('name_en', $table->name_en, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('store.name_en'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('parent_id', __('store.head_store')) !!}
                {!! Form::select('parent_id', $mainStores, $table->parent_id, [
                    'class' => 'form-control',
                    'placeholder' => __('messages.please_select'),
                    'required',
                ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('type_cost', __('store.type_cost')) !!}
                {!! Form::select('type_cost', $cost_type, $table->type_cost, [
                    'class' => 'form-control',
                    'placeholder' => __('messages.please_select'),
                    'required',
                    'data-disabled' => 'true',
                ]) !!}
                <input type="hidden" name="type_cost_hidden" id="type_cost_hidden" value="{{ $table->type_cost }}">
            </div>
            <div class="form-group">
                {!! Form::label('store_type', __('store.store_type')) !!}
                {!! Form::select('store_type', $store_type, $table->store_type, [
                    'class' => 'form-control',
                    'placeholder' => __('messages.please_select'),
                    'required',
                    'data-disabled' => 'true',
                ]) !!}
                <input type="hidden" name="store_type_hidden" id="store_type_hidden" value="{{ $table->store_type }}">
            </div>

            <div class="form-group">
                {!! Form::label('description', __('store.description') . ':') !!}
                {!! Form::text('description', $table->description, [
                    'class' => 'form-control',
                    'placeholder' => __('store.description'),
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status', __('store.status') . ':*') !!}
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
<script>
    $(document).ready(function() {
        function updateSelectState(selectId, value) {
            var $select = $('#' + selectId);
            var $hidden = $('#' + selectId + '_hidden');
            if ($select.attr('data-disabled') === 'true') {
                $select.addClass('disabled-look').prop('disabled', true);
                if (value !== undefined) {
                    $select.val(value);
                }
                $hidden.val($select.val());
            } else {
                $select.removeClass('disabled-look').prop('disabled', false);
                $hidden.val('');
            }
        }

        var initialTypeCost = $('#type_cost').val();
        var initialStoreType = $('#store_type').val();

        updateSelectState('type_cost', initialTypeCost);
        updateSelectState('store_type', initialStoreType);

        $('#parent_id').on('change', function() {
            var parentId = $(this).val();
            if (parentId) {
                $.ajax({
                    url: '/modules/subs-stories/get-store-details/' + parentId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        updateSelectState('type_cost', data.type_cost);
                        updateSelectState('store_type', data.store_type);
                    },
                    error: function() {
                        alert('Error fetching store details');
                    }
                });
            } else {
                updateSelectState('type_cost', '');
                updateSelectState('store_type', '');
            }
        });

        // تشغيل التغيير الأولي إذا كان هناك قيمة في parent_id
        var initialParentId = $('#parent_id').val();
        if (initialParentId) {
            $('#parent_id').trigger('change');
        }
    });
</script>
