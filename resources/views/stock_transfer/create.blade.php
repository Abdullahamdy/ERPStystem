@extends('layouts.app')
@section('title', __('lang_v1.add_stock_transfer'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.add_stock_transfer')</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        {!! Form::open([
            'url' => action('StockTransferController@store'),
            'method' => 'post',
            'id' => 'stock_transfer_form',
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('transaction_date', __('messages.date') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('ref_no', __('purchase.ref_no') . ':') !!}
                            {!! Form::text('ref_no', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('status', __('sale.status') . ':*') !!} @show_tooltip(__('lang_v1.completed_status_help'))
                            {!! Form::select('status', $statuses, null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                                'id' => 'status',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('status', __('نوع التحويل') . ':*') !!} @show_tooltip(__('lang_v1.completed_status_help'))
                            {!! Form::select('status', $transferType, null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                                'id' => 'transferType',
                                'name'=>'transfer_type'
                            ]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-sm-6 location_from" style="visibility: hidden;">
                        <div class="form-group">
                            {!! Form::label('location_id', __('lang_v1.location_from') . ':*') !!}
                            {!! Form::select('location_id', $business_locations, null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'id' => 'location_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6 store_from" style="visibility: hidden;">
                        <div class="form-group">
                            {!! Form::label('location_id', 'من مخزن ' . ':*') !!}
                            {!! Form::select('location_id', [], null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                                'id' => 'location_id',
                                'name' => 'store_from',
                            ]) !!}
                        </div>
                    </div>



                    <div class="col-sm-6 location_to" style="visibility: hidden;">
                        <div class="form-group">
                            {!! Form::label('transfer_location_id', __('lang_v1.location_to').':*') !!}
                            {!! Form::select('transfer_location_id', $business_locations, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'id' => 'transfer_location_id']); !!}
                        </div>
                    </div>
                    <div class="col-sm-6 store_to" style="visibility: hidden;">
                        <div class="form-group">
                            {!! Form::label('location_id', 'إلي مخزن ' . ':*') !!}
                            {!! Form::select('location_id', [], null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                                'id' => 'location_id',
                                'name' => 'store_to',
                            ]) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div> <!--box end-->
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">{{ __('stock_adjustment.search_products') }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                {!! Form::text('search_product', null, [
                                    'class' => 'form-control',
                                    'id' => 'search_product_for_srock_adjustment',
                                    'placeholder' => __('stock_adjustment.search_product'),
                                    'disabled',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <input type="hidden" id="product_row_index" value="0">
                        <input type="hidden" id="total_amount" name="final_total" value="0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed"
                                id="stock_adjustment_product_table">
                                <thead>
                                    <tr>
                                        <th class="col-sm-4 text-center">
                                            @lang('sale.product')
                                        </th>
                                        <th class="col-sm-2 text-center">
                                            @lang('sale.qty')
                                        </th>
                                        <th class="col-sm-2 text-center">
                                            @lang('sale.unit_price')
                                        </th>
                                        <th class="col-sm-2 text-center">
                                            @lang('sale.subtotal')
                                        </th>
                                        <th class="col-sm-2 text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <td colspan="3"></td>
                                        <td>
                                            <div class="pull-right"><b>@lang('sale.total'):</b> <span
                                                    id="total_adjustment">0.00</span></div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--box end-->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('shipping_charges', __('lang_v1.shipping_charges') . ':') !!}
                            {!! Form::text('shipping_charges', 0, [
                                'class' => 'form-control input_number',
                                'placeholder' => __('lang_v1.shipping_charges'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('additional_notes', __('purchase.additional_notes')) !!}
                            {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <b>@lang('stock_adjustment.total_amount'):</b> <span id="final_total_text">0.00</span>
                    </div>
                    <br>
                    <br>
                    <div class="col-sm-12">
                        <button type="submit" id="save_stock_transfer"
                            class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>

            </div>
        </div> <!--box end-->
        {!! Form::close() !!}
    </section>
@stop
@section('javascript')
    <script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        __page_leave_confirmation('#stock_transfer_form');
    </script>



    <script type="text/javascript">
       $(document).ready(function() {
    const $transferType = $('#transferType');
    const $locationFrom = $('#location_id');
    const $locationTo = $('#transfer_location_id');
    const $storeFrom = $('select[name="store_from"]');
    const $storeTo = $('select[name="store_to"]');
    const $allFields = $('.location_from, .store_from, .location_to, .store_to');

    function toggleFieldsVisibility(transferType) {
        if (transferType === '') {
            $allFields.css('visibility', 'hidden');
        } else if (transferType === '0') { // Internal transfer
            $('.location_from, .store_from, .store_to').css('visibility', 'visible');
            $('.location_to').css('visibility', 'hidden');
        } else if (transferType === '1') { // External transfer
            $allFields.css('visibility', 'visible');
        }
    }

    function updateStores(branchId, targetSelect) {
        if (!branchId) {
            targetSelect.empty().append($('<option>', {
                value: '',
                text: targetSelect.data('placeholder') || 'الرجاء الاختيار'
            }));
            return;
        }

        $.ajax({
            url: `/products/get-stores/${branchId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                targetSelect.empty().append($('<option>', {
                    value: '',
                    text: targetSelect.data('placeholder') || 'الرجاء الاختيار'
                }));
                if (data && typeof data === 'object' && Object.keys(data).length) {
                    $.each(data, function(key, value) {
                        if (key && value) {
                            targetSelect.append($('<option>', {
                                value: key,
                                text: value
                            }));
                        }
                    });
                } else {
                    console.warn('No valid store data received');
                }
                targetSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching store details:', error);
                alert('حدث خطأ أثناء جلب تفاصيل المخزن. يرجى المحاولة مرة أخرى.');
            }
        });
    }

    function handleTransferTypeChange() {
        const transferType = $transferType.val();
        toggleFieldsVisibility(transferType);
        $storeFrom.empty().append($('<option>', {
            value: '',
            text: $storeFrom.data('placeholder') || 'الرجاء الاختيار'
        }));
        $storeTo.empty().append($('<option>', {
            value: '',
            text: $storeTo.data('placeholder') || 'الرجاء الاختيار'
        }));
        if (transferType === '0') {
            updateStores($locationFrom.val(), $storeFrom);
            updateStores($locationFrom.val(), $storeTo);
        }
    }

    $transferType.on('change', handleTransferTypeChange);

    $locationFrom.on('change', function() {
        const branchId = $(this).val();
        const transferType = $transferType.val();
        if (transferType === '0') {
            updateStores(branchId, $storeFrom);
            updateStores(branchId, $storeTo);
        } else if (transferType === '1') {
            updateStores(branchId, $storeFrom);
        }
    });

    $locationTo.on('change', function() {
        const branchId = $(this).val();
        const transferType = $transferType.val();
        if (transferType === '1') {
            updateStores(branchId, $storeTo);
        }
    });

    // Initialize select2 for all select elements
    $('.select2').select2();

    // Initialize fields on page load
    $allFields.css('visibility', 'hidden');
    // Check initial value of transferType
    handleTransferTypeChange();
});
    </script>
@endsection