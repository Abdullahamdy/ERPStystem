<style>
    /* Main Panel Styling */
    .pos_form_totals {
        margin-top: 20px;
        padding: 15px;
        background: #f5f7fa;
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table-condensed {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .table-condensed td {
        padding: 10px;
        font-size: 16px;
        vertical-align: middle;
    }

    .table-condensed b {
        color: #333;
        font-weight: 600;
    }

    /* Icon Styling */
    .fas.fa-edit {
        color: #2196f3;
        margin-left: 5px;
        cursor: pointer;
        font-size: 1.1em;
    }

    /* Button Hover Effect */
    .fas.fa-edit:hover {
        color: #0d47a1;
    }

    /* Tax, Discount, Shipping Section */
    .form-control {
        border-radius: 8px;
    }

    /* Total Payable Section */
    .total_payable_section {
        text-align: center;
        padding: 15px 0;
        background-color: #e8f5e9;
        border-radius: 10px;
        font-weight: bold;
        color: #388e3c;
        font-size: 1.2em;
    }

    /* Shipping, Discount, and Tax Icon Edit */
    .highlight-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .highlight-edit .edit-icon {
        color: #3b82f6;
        cursor: pointer;
    }

    .highlight-edit .edit-icon:hover {
        color: #087f23;
    }

    /* Flex Layout Adjustments */
    .flex-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .input-hidden {
        display: none;
    }
    .table-condensed, .table-condensed td {
    border: 1px solid #ddd; /* Sets the color and thickness of the borders */
}
</style>



<div class="row pos_form_totals">
    <div class="col-md-12">
        <table class="table table-condensed">
            <tr>
                <!-- Total Quantity -->
                {{-- <td>
                    <b>@lang('sale.item'):</b> &nbsp;
                    <span class="total_quantity">0</span>
                </td> --}}

                <!-- Total Price -->
                <td>
                    <b>@lang('sale.sub_total'):</b> &nbsp;
                    <span class="price_total">0</span>
                </td>

                <td>
                    <b>
                        @if($is_discount_enabled)
                            @lang('sale.discount') (-): 
                        @endif
                    </b> 
                    <div class="highlight-edit">
                        @if($is_discount_enabled)
                            <span id="total_discount">0</span>
                            @if($edit_discount)
                                <i class="fas fa-edit edit-icon" id="pos-edit-discount" title="@lang('sale.edit_discount')" data-toggle="modal" data-target="#posEditDiscountModal"></i>
                            @endif
                        @endif
                    </div>
                    <input type="hidden" name="discount_type" id="discount_type" value="@if(empty($edit)){{'percentage'}}@else{{$transaction->discount_type}}@endif" data-default="percentage">
                    <input type="hidden" name="discount_amount" id="discount_amount" value="@if(empty($edit)) {{@num_format($business_details->default_sales_discount)}} @else {{@num_format($transaction->discount_amount)}} @endif" data-default="{{$business_details->default_sales_discount}}">
                    <input type="hidden" name="rp_redeemed" id="rp_redeemed" value="@if(empty($edit)){{'0'}}@else{{$transaction->rp_redeemed}}@endif">
                    <input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="@if(empty($edit)){{'0'}}@else {{$transaction->rp_redeemed_amount}} @endif">
                </td>

                <td>
                    <b>@lang('sale.shipping')(+):</b>
                    <div class="highlight-edit">
                        <span id="shipping_charges_amount">0</span>
                        <i class="fas fa-edit edit-icon" title="@lang('sale.shipping')" data-toggle="modal" data-target="#posShippingModal"></i>
                    </div>
                    <input type="hidden" name="shipping_details" id="shipping_details" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_details}}@endif">
                    <input type="hidden" name="shipping_address" id="shipping_address" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_address}}@endif">
                    <input type="hidden" name="shipping_status" id="shipping_status" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_status}}@endif">
                    <input type="hidden" name="delivered_to" id="delivered_to" value="@if(empty($edit)){{''}}@else{{$transaction->delivered_to}}@endif">
                    <input type="hidden" name="shipping_charges" id="shipping_charges" value="@if(empty($edit)){{@num_format(0.00)}} @else{{@num_format($transaction->shipping_charges)}} @endif" data-default="0.00">
                </td>
            </tr>
            <tr>
                <!-- Discount -->
            

                <!-- Order Tax -->
                <td class="@if($pos_settings['disable_order_tax'] != 0) hide @endif">
                    <b>@lang('sale.order_tax')(+):</b>
                    <div class="highlight-edit">
                        <span id="order_tax">
                            @if(empty($edit))
                                0
                            @else
                                {{$transaction->tax_amount}}
                            @endif
                        </span>
                        <i class="fas fa-edit edit-icon" title="@lang('sale.edit_order_tax')" data-toggle="modal" data-target="#posEditOrderTaxModal" id="pos-edit-tax"></i>
                    </div>
                    <input type="hidden" name="tax_rate_id" id="tax_rate_id" value="@if(empty($edit)) {{$business_details->default_sales_tax}} @else {{$transaction->tax_id}} @endif" data-default="{{$business_details->default_sales_tax}}">
                    <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" value="@if(empty($edit)) {{@num_format($business_details->tax_calculation_amount)}} @else {{@num_format(optional($transaction->tax)->amount)}} @endif" data-default="{{$business_details->tax_calculation_amount}}">
                </td>

                <!-- Shipping Charges -->
               

                {{-- <!-- Packing Charge (optional) -->
                @if(in_array('types_of_service', $enabled_modules))
                    <td>
                        <b>@lang('lang_v1.packing_charge')(+):</b>
                        <span id="packing_charge_text">0</span>
                    </td>
                @endif --}}
                <td> </td>
                <td>
                    <b>@lang('المجموع الكلي')(+):</b>
                    <span id="ttotal_price">0</span>
                </td>
                <!-- Round Off (if applicable) -->
                @if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
                    <td>
                        <b>@lang('lang_v1.round_off'):</b> 
                        <span id="round_off_text">0</span>
                        <input type="hidden" name="round_off_amount" id="round_off_amount" value="0">
                    </td>
                @endif
            </tr>
        </table>
    </div>
</div>


