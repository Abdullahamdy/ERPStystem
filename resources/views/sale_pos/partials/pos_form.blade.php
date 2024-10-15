<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id'] ?? ''}}">
                <input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name'] ?? ''}}">
                <input type="hidden" id="default_customer_balance" value="{{ $walk_in_customer['balance'] ?? ''}}">
                <input type="hidden" id="default_customer_address" value="{{ $walk_in_customer['shipping_address'] ?? ''}}">
                @if(!empty($walk_in_customer['price_calculation_type']) && $walk_in_customer['price_calculation_type'] == 'selling_price_group')
                <input type="hidden" id="default_selling_price_group" value="{{ $walk_in_customer['selling_price_group_id'] ?? ''}}">
                @endif
                {!! Form::select('contact_id', [], null, ['class' => 'form-control mousetrap', 'id' => 'customer_id', 'placeholder' => 'Enter Customer name / phone', 'required']) !!}
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name="" @if(!auth()->user()->can('customer.create')) disabled @endif><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                </span>
            </div>
            <small class="text-danger hide contact_due_text"><strong>@lang('account.customer_due'):</strong> <span></span></small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group" style="    margin-right: 610px;
    padding-top: 88px;">
            <div class="input-group">
                <div class="input-group-btn" style=" z-index:999999999999999999999999999 !important;">
                    <button type="button" class="btn btn-default bg-white btn-flat" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}"><i class="fas fa-search-plus"></i></button>
                </div>
                {!! Form::text('search_product', null, ['class' => 'form-control mousetrap ss', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'),
                'disabled' => is_null($default_location)? true : false,
                'autofocus' => is_null($default_location)? false : true,
                ])  !!}
                <span class="input-group-btn">
                    @if(isset($pos_settings['enable_weighing_scale']) && $pos_settings['enable_weighing_scale'] == 1)
                    <button type="button" class="btn btn-default bg-white btn-flat" id="weighing_scale_btn" data-toggle="modal" data-target="#weighing_scale_modal" title="@lang('lang_v1.weighing_scale')"><i class="fa fa-digital-tachograph text-primary fa-lg"></i></button>
                    @endif
                    <button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product" data-href="{{action('ProductController@quickAdd')}}" data-container=".quick_add_product_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @if(!empty($pos_settings['show_invoice_layout']))
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::select('invoice_layout_id', $invoice_layouts, $default_location->invoice_layout_id, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_invoice_layout'), 'id' => 'invoice_layout_id']) !!}
        </div>
    </div>
    @endif
    <input type="hidden" name="pay_term_number" id="pay_term_number" value="{{$walk_in_customer['pay_term_number'] ?? ''}}">
    <input type="hidden" name="pay_term_type" id="pay_term_type" value="{{$walk_in_customer['pay_term_type'] ?? ''}}">
    @if(!empty($commission_agent))
    @php
    $is_commission_agent_required = !empty($pos_settings['is_commission_agent_required']);
    @endphp
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::select('commission_agent', $commission_agent, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.commission_agent'), 'id' => 'commission_agent', 'required' => $is_commission_agent_required]) !!}
        </div>
    </div>
    @endif
    @if(!empty($pos_settings['enable_transaction_date']))
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                {!! Form::text('transaction_date', $default_datetime, ['class' => 'form-control', 'readonly', 'required', 'id' => 'transaction_date']) !!}
            </div>
        </div>
    </div>
    @endif
    @if(config('constants.enable_sell_in_diff_currency') == true)
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-exchange-alt"></i>
                </span>
                {!! Form::text('exchange_rate', config('constants.currency_exchange_rate'), ['class' => 'form-control input-sm input_number', 'placeholder' => __('lang_v1.currency_exchange_rate'), 'id' => 'exchange_rate']) !!}
            </div>
        </div>
    </div>
    @endif
    @if(!empty($price_groups) && count($price_groups) > 1)
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                @php
                reset($price_groups);
                $selected_price_group = !empty($default_price_group_id) && array_key_exists($default_price_group_id, $price_groups) ? $default_price_group_id : null;
                @endphp
                {!! Form::hidden('hidden_price_group', key($price_groups), ['id' => 'hidden_price_group']) !!}
                {!! Form::select('price_group', $price_groups, $selected_price_group, ['class' => 'form-control select2', 'id' => 'price_group']) !!}
                <span class="input-group-addon">
                    @show_tooltip(__('lang_v1.price_group_help_text'))
                </span>
            </div>
        </div>
    </div>
    @else
    @php
    reset($price_groups);
    @endphp
    {!! Form::hidden('price_group', key($price_groups), ['id' => 'price_group']) !!}
    @endif
    @if(!empty($default_price_group_id))
    {!! Form::hidden('default_price_group', $default_price_group_id, ['id' => 'default_price_group']) !!}
    @endif

 @if(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
<div class="col-md-4 col-sm-6">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-external-link-square-alt text-primary service_modal_btn"></i>
            </span>

            <!-- Replace dropdown with buttons -->
            <div id="types_of_service_buttons">
                <button type="button" class="btn btn-primary service-btn" data-value="داخلي">داخلي</button>
                <button type="button" class="btn btn-secondary service-btn" data-value="تيك اوي">تيك اوي</button>
                <button type="button" class="btn btn-secondary service-btn" data-value="ديلفري">ديلفري</button>
            </div>
        </div>
        <small>
            <p class="help-block hide" id="price_group_text">@lang('lang_v1.price_group'): <span></span></p>
        </small>
    </div>
</div>

<!-- Modal remains the same -->
<div class="modal fade types_of_service_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endif

    @if(!empty($pos_settings['show_invoice_scheme']))
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            {!! Form::select('invoice_scheme_id', $invoice_schemes, $default_invoice_schemes->id, ['class' => 'form-control', 'placeholder' => __('lang_v1.select_invoice_scheme')]) !!}
        </div>
    </div>
    @endif
    @if(in_array('subscription', $enabled_modules))
    <div class="col-md-4 col-sm-6">
        <label>
            {!! Form::checkbox('is_recurring', 1, false, ['class' => 'input-icheck', 'id' => 'is_recurring']) !!} @lang('lang_v1.subscribe')?
        </label>
        <button type="button" data-toggle="modal" data-target="#recurringInvoiceModal" class="btn btn-link"><i class="fa fa-external-link-square-alt"></i></button>@show_tooltip(__('lang_v1.recurring_invoice_help'))
    </div>
    @endif
    @if(in_array('tables' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
  <div class="clearfix"></div>
    <span id="restaurant_module_span" style="display: none;">
        <div class="col-md-3"></div>
    </span>
    @endif
</div>

<!-- Include Module Fields -->
@if(!empty($pos_module_data))
@foreach($pos_module_data as $key => $value)
@if(!empty($value['view_path']))
@includeIf($value['view_path'], ['view_data' => $value['view_data']])
@endif
@endforeach
@endif

<div class="row">
    <div class="col-sm-12 pos_product_div">
        <input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">
        <input type="hidden" id="product_row_count" value="0">
        @php
        $hide_tax = session()->get('business.enable_inline_tax') == 0 ? 'hide' : '';
        @endphp
        <table class="table table-condensed table-bordered table-striped table-responsive" id="pos_table">
            <thead>
                <tr>
                    <th class="tex-center @if(!empty($pos_settings['inline_service_staff'])) col-md-3 @else col-md-4 @endif">
                        @lang('sale.product') @show_tooltip(__('lang_v1.tooltip_sell_product_column'))
                    </th>
                    <th class="text-center col-md-3">
                        @lang('sale.qty')
                    </th>
                    @if(!empty($pos_settings['inline_service_staff']))
                    <th class="text-center col-md-2">
                        @lang('restaurant.service_staff')
                    </th>
                    @endif
                    <th class="text-center col-md-2 {{$hide_tax}}">
                        @lang('sale.price_inc_tax')
                    </th>
                    <th class="text-center col-md-2">
                        @lang('sale.subtotal')
                    </th>
                    <th class="text-center"><i class="fas fa-times" aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>


<style>
    /* Styling for the Main Form and Input Fields */
    .pos_product_div {
        background: #ffffff;
        padding: 20px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-top: 20px;
    }

    #pos_table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
    }

    #pos_table thead tr {
        background: #f5f5f5;
        color: #333;
        font-weight: bold;
    }

    #pos_table th,
    #pos_table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #pos_table tbody tr {
        transition: background-color 0.2s ease;
    }

    #pos_table tbody tr:hover {
        background-color: #e8f7e7;
    }

    /* Selected Item Row Styling */
    #pos_table tbody tr.selected {
        background-color: #4caf50 !important;
        color: #fff;
    }

    .input-group-addon {
        background: #fff;
        border: none;
    }

    .form-group .input-group .form-control {
        border-radius: 0;
        box-shadow: none;
        border: 1px solid #ddd;
    }
.ss{
       width: 730px !important;
        /*max-width: 400px;*/
        padding: 17px 15px !important;
        border: 1px solid #ddd !important;
        border-radius: 25px !important;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1) !important;
        outline: none !important;
}
    /* Buttons */
    .btn-flat {
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Order Summary Styling */
    .pos_form_totals {
        background: #ffffff;
        padding: 10px;
        margin-top: 20px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* Table Row Styling */
    .pos_form_totals table {
        width: 100%;
        border-collapse: collapse;
    }

    .pos_form_totals td {
        padding: 15px;
        border: none;
        font-weight: bold;
        color: #333;
        text-align: center;
    }

    /* Product List Section */
    .product_list {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    /* Flex Layout for Header and Input Fields */
    .row .form-group {
        display: flex;
        align-items: center;
    }

    /* General UI Improvements */
    .text-danger.hide.contact_due_text {
        display: none;
    }

    .text-danger.contact_due_text {
        font-size: 14px;
    }

    .highlight-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .highlight-edit .edit-icon {
        color: #4caf50;
        cursor: pointer;
    }

    .highlight-edit .edit-icon:hover {
        color: #087f23;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the buttons and restaurant module span elements
        const serviceButtons = document.querySelectorAll('.service-btn');
        const restaurantModuleSpan = document.getElementById('restaurant_module_span');

        // Function to show/hide restaurant module
        function toggleRestaurantModule(selectedValue) {
            if (selectedValue === 'داخلي') {
                restaurantModuleSpan.style.display = 'block';  // Show the restaurant module
            } else {
                restaurantModuleSpan.style.display = 'none';  // Hide the restaurant module
            }
        }

        // Add event listeners for each button
        serviceButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Remove 'btn-primary' from all buttons and add 'btn-secondary'
                serviceButtons.forEach(btn => btn.classList.remove('btn-primary'));
                serviceButtons.forEach(btn => btn.classList.add('btn-secondary'));

                // Add 'btn-primary' to the clicked button
                this.classList.remove('btn-secondary');
                this.classList.add('btn-primary');

                // Call the function to toggle the restaurant module
                const selectedValue = this.getAttribute('data-value');
                toggleRestaurantModule(selectedValue);
            });
        });

        // Initial check (Optional: to pre-select a button if needed)
        // toggleRestaurantModule('داخلي'); // For example, you can pass a default value here
    });
</script>