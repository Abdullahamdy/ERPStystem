@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('css')
<style>
    /* Modern Styling for POS Page */
/*    body {*/
/*        font-family: 'Roboto', sans-serif;*/
/*    }*/

/*    .pos-container {*/
/*        padding: 20px;*/
/*        background: #f5f7fa;*/
/*    }*/

/*    .box {*/
/*        background: #fff;*/
/*        border-radius: 15px;*/
/*        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);*/
/*        padding: 20px;*/
/*        margin-bottom: 20px;*/
/*    }*/

/*    .box-body {*/
/*        padding: 20px;*/
/*    }*/

/*    .input-group {*/
/*        border-radius: 12px;*/
/*        overflow: hidden;*/
/*        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.05);*/
/*    }*/

/*    .input-group-addon, .input-group-btn button {*/
/*        background: #ffffff;*/
/*        border: 1px solid #e0e0e0;*/
/*        color: #333;*/
/*        font-size: 16px;*/
/*    }*/

/*    .input-group-addon {*/
/*        border-right: 0;*/
/*    }*/

/*    .form-control {*/
/*        border: 1px solid #e0e0e0;*/
/*        font-size: 16px;*/
/*        color: #333;*/
/*    }*/

    /* Product Cards */
/*    .pos-product-tile {*/
/*        background: #ffffff;*/
/*        padding: 15px;*/
/*        border-radius: 15px;*/
/*        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);*/
/*        text-align: center;*/
/*        cursor: pointer;*/
/*        transition: transform 0.3s ease, box-shadow 0.3s ease;*/
/*        position: relative;*/
/*    }*/

/*    .pos-product-tile:hover {*/
/*        transform: scale(1.03);*/
/*        box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.12);*/
/*    }*/

/*    .product-price-badge {*/
/*        background-color: #3b82f6;*/
/*        color: #fff;*/
/*        padding: 6px 12px;*/
/*        border-radius: 15px;*/
/*        font-weight: 700;*/
/*        position: absolute;*/
/*        top: 10px;*/
/*        right: 10px;*/
/*        font-size: 14px;*/
/*    }*/

/*    .product-availability {*/
/*        color: #16a34a;*/
/*        font-size: 14px;*/
/*        font-weight: 600;*/
/*        margin-top: 8px;*/
/*    }*/

    /* Category Scroll Tabs */
/*    .category-scroll {*/
/*        overflow-x: auto;*/
/*        white-space: nowrap;*/
/*        padding: 10px 0;*/
/*        margin-bottom: 20px;*/
/*    }*/

/*    .category-tab {*/
/*        background-color: #2563eb;*/
/*        color: #ffffff;*/
/*        padding: 10px 20px;*/
/*        border-radius: 25px;*/
/*        margin-right: 15px;*/
/*        cursor: pointer;*/
/*        transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;*/
/*        display: inline-block;*/
/*    }*/

/*    .category-tab:hover {*/
/*        background-color: #1d4ed8;*/
/*        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);*/
/*    }*/

    /* Action Buttons */
/*    .btn-flat {*/
/*        border-radius: 10px;*/
/*        padding: 10px 20px;*/
/*        font-size: 16px;*/
/*        color: #ffffff;*/
/*        background: #3b82f6;*/
/*        transition: background-color 0.3s ease;*/
/*    }*/

/*    .btn-flat:hover {*/
/*        background: #2563eb;*/
/*    }*/

/*    .pos_form_actions {*/
/*        display: flex;*/
/*        justify-content: space-between;*/
/*        padding-top: 20px;*/
/*    }*/

    /* Product Search */
/*    #search_product {*/
/*        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.05);*/
/*    }*/

    /* Recent Transactions Modal */
/*    .modal-content {*/
/*        border-radius: 15px;*/
/*        box-shadow: 0px 12px 25px rgba(0, 0, 0, 0.2);*/
/*    }*/

    /* Enhancing Tables */
/*    .table {*/
/*        margin-top: 20px;*/
/*        background: #ffffff;*/
/*        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);*/
/*    }*/

/*    .table th, .table td {*/
/*        vertical-align: middle;*/
/*        border-bottom: 1px solid #e0e0e0;*/
/*    }*/

/*    .table th {*/
/*        background-color: #2563eb;*/
/*        color: #ffffff;*/
/*        font-weight: bold;*/
/*    }*/

    /* Custom Radio and Checkbox */
/*    .input-icheck {*/
/*        accent-color: #2563eb;*/
/*    }*/


 /* Search Bar Styling */
 .form-control {
    display: block;
    width: 100%;
    height: 27.5px !important;
    padding: 0px 12px !important;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
 .pos-header {
     display: none !important;
 }
    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .search-bar {
           width: 730px !important;
        /*max-width: 400px;*/
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 25px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        outline: none;
    }

    .product_box .image-container {
     width: 95%; 
    height: 109px !important;
    margin: auto !important;
    border-radius: 13px !important;
     padding-top: 5px; 
}

.product_list {
    padding-left: 0px !important;
    padding-right: 0px !important;
}
  .pos-container {
        padding: 20px;
        background: #f5f7fa;
    }

    /* Product Grid Styling */
    .product-grid {
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 20px;
        overflow-y: auto;
    }

    .product_box {
        position: relative;
        border-radius: 15px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        cursor: pointer;
        background: #ffffff;
        padding: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .product_box:hover {
        transform: scale(1.03);
        box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.12);
    }

    .price-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #3b82f6 !important;
        color: #fff;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 14px;
    }

    .image-container {
        height: 120px;
        width: 100%;
        background-size: cover !important;
        background-position: center;
        border-radius: 10px;
    }

    .text_div {
        padding: 10px;
    }

    .product-name {
        font-weight: 600;
        font-size: 16px;
        margin-top: 10px;
        color: #333;
    }

    .product-details {
        color: #777;
        font-size: 14px;
        margin-top: 5px;
    }


.main-header {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    padding: 10px 20px;
}

.top-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.left-nav,
.right-nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

.left-nav .dropdown-btn,
.icon-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2em;
    display: flex;
    align-items: center;
}

.left-nav .dropdown-btn {
    display: flex;
    align-items: center;
}

.icon-btn {
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.icon-btn:hover {
    background-color: #f0f0f0;
}

.nav-link {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.nav-link i {
    margin-right: 5px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
    font-size: 1.2em;
}

.logo img {
    width: 50px;
    height: 50px;
}

.bottom-nav {
    display: flex;
    border-top: 1px solid #ddd;
    padding: 10px 0;
}

.icon-links {
    display: flex;
    gap: 25px;
}

.icon-link {
    text-align: center;
    cursor: pointer;
    transition: color 0.3s;
    color:#2196f3 !important;
}

.icon-link:hover {
    color: #3b82f6;
}

.icon-link i {
    font-size: 1.0em !important;
    margin-left: 3px !important;
}

.icon-link span {
    display: block;
    font-size: 0.8em;
    color: #333;
    margin-top: 5px;
    margin-left: 5px !important;

}
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-btn {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.dropdown-content {
    display: none; /* Hidden by default */
    position: absolute;
    right: 0;
    background-color: #ffffff;
    min-width: 200px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    z-index: 1;
    border-radius: 5px;
    overflow: hidden;
}

.dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dropdown-content a:hover {
    background-color: #3b82f6;
    color: #ffffff;
}

.dropdown:hover .dropdown-content {
    display: block; /* Show on hover */
}
</style>
@endsection
 <header class="main-header">
        <div class="top-nav">
            <div class="left-nav">
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <a href="https://srt-saudiroad.com/user/profile">
                        <i class="fas fa-user-circle"></i> حسابي</a>
                    </button>
                    <!-- Dropdown content if needed -->
                </div>
                <!--<button class="icon-btn">-->
                <!--    <i class="fas fa-globe"></i>-->
                <!--</button>-->
                <button class="icon-btn">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="icon-btn">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
            <div class="right-nav">
                <a href="https://srt-saudiroad.com/home/" class="nav-link">لوحة القيادة</a>
                <!--<a href="#" class="nav-link">تاريخ الطلب</a>-->
                <a href="https://srt-saudiroad.com/sells" class="nav-link"><i class="fas fa-book"></i> السجل</a>
                <a href=" https://srt-saudiroad.com/modules/kitchen" class="nav-link"><i class="fas fa-book"></i> المطبخ</a>
                
                <a href="https://srt-saudiroad.com/pos/" class="nav-link"><i class="fas fa-store-alt"></i> نقاط البيع</a>
            </div>
            <div class="logo">
                <img src="https://srt-saudiroad.com/public/logo.png" alt="Logo">
                <span>saudiroad</span>
            </div>
        </div>
      <!-- Integrated POS Action Sidebar styled as Bottom Navigation -->
    <div class="bottom-nav" style=" display:block !important;">
        <div class="icon-links">
            <!-- Draft Button -->
            <div class="icon-link">
                <button type="button" class="btn icon-btn @if($pos_settings['disable_draft'] != 0) hide @endif" id="pos-draft">
                    <i class="fas fa-edit"></i>
                    <span>@lang('sale.draft')</span>
                </button>
            </div>

            <!-- Quotation Button -->
            <div class="icon-link">
                <button type="button" class="btn icon-btn" id="pos-quotation">
                    <i class="fas fa-edit"></i>
                    <span>@lang('lang_v1.quotation')</span>
                </button>
            </div>

            <!-- Suspend Button -->
            @if(empty($pos_settings['disable_suspend']))
            <div class="icon-link">
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="suspend" title="@lang('lang_v1.tooltip_suspend')">
                    <i class="fas fa-pause"></i>
                    <span>@lang('lang_v1.suspend')</span>
                </button>
            </div>
            @endif

            <!-- Credit Sale Button -->
            @if(empty($pos_settings['disable_credit_sale_button']))
            <div class="icon-link">
                <input type="hidden" name="is_credit_sale" value="0" id="is_credit_sale">
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="credit_sale" title="@lang('lang_v1.tooltip_credit_sale')">
                    <i class="fas fa-check"></i>
                    <span>@lang('lang_v1.credit_sale')</span>
                </button>
            </div>
            @endif

            <!-- Express Checkout Card Button -->
            <div class="icon-link">
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="card" title="@lang('lang_v1.tooltip_express_checkout_card')" @if(!array_key_exists('card', $payment_types)) style="display:none;" @endif>
                    <i class="fas fa-credit-card"></i>
                    <span>@lang('lang_v1.express_checkout_card')</span>
                </button>
            </div>

            <!-- Finalize Sale Button -->
            <div class="icon-link">
                <button type="button" class="btn icon-btn no-print" id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')">
                    <i class="fas fa-money-check-alt"></i>
                    <span>@lang('lang_v1.checkout_multi_pay')</span>
                </button>
            </div>

            <!-- Express Checkout Cash Button -->
            <div class="icon-link">
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="cash" title="@lang('tooltip.express_checkout')" @if($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) style="display:none;" @endif>
                    <i class="fas fa-money-bill-alt"></i>
                    <span>@lang('lang_v1.express_checkout_cash')</span>
                </button>
            </div>

            <!-- Cancel Button -->
            @if(empty($edit))
            <div class="icon-link">
                <button type="button" class="btn icon-btn" id="pos-cancel">
                    <i class="fas fa-window-close"></i>
                    <span>@lang('sale.cancel')</span>
                </button>
            </div>
            @else
            <div class="icon-link">
                <button type="button" class="btn icon-btn hide" id="pos-delete">
                    <i class="fas fa-trash-alt"></i>
                    <span>@lang('messages.delete')</span>
                </button>
            </div>
            @endif

            <!-- Recent Transactions Button -->
            @if(!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
            <div class="icon-link">
                <button type="button" class="btn icon-btn" data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions">
                    <i class="fas fa-clock"></i>
                    <span>@lang('lang_v1.recent_transactions')</span>
                </button>
            </div>
            @endif
        </div>

</header>

@section('content')
<section class="content no-print pos-container">
    <input type="hidden" id="amount_rounding_method" value="{{ $pos_settings['amount_rounding_method'] ?? '' }}">
    @if(!empty($pos_settings['allow_overselling']))
        <input type="hidden" id="is_overselling_allowed">
    @endif
    @if(session('business.enable_rp') == 1)
        <input type="hidden" id="reward_point_enabled">
    @endif

    @php
        $is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
        $is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
    @endphp

    {!! Form::open(['url' => action('SellPosController@store'), 'method' => 'post', 'id' => 'add_pos_sell_form' ]) !!}
    <div class="row mb-12">
        <div class="col-md-12">
            <div class="row">
                <!-- Customer Info and Product Search Section -->
                <div class="col-md-4 no-padding pr-12">
                    <div class="box box-solid mb-12 mb-40">
                        <div class="box-body pb-0">
                            {!! Form::hidden('location_id', optional($default_location)->id , ['id' => 'location_id', 'data-receipt_printer_type' => optional($default_location)->receipt_printer_type ?? 'browser', 'data-default_payment_accounts' => optional($default_location)->default_payment_accounts ?? '']) !!}
                            {!! Form::hidden('sub_type', $sub_type ?? null) !!}
                            <input type="hidden" id="item_addition_method" value="{{ optional($business_details)->item_addition_method }}">

                            @include('sale_pos.partials.pos_form')

                            @include('sale_pos.partials.pos_form_totals')

                            @include('sale_pos.partials.payment_modal')

                            @if(empty($pos_settings['disable_suspend']))
                                @include('sale_pos.partials.suspend_note_modal')
                            @endif

                            @if(empty($pos_settings['disable_recurring_invoice']))
                                @include('sale_pos.partials.recurring_invoice_modal')
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Product Sidebar for Suggestions -->
                @if(empty($pos_settings['hide_product_suggestion']) && !isMobile())
                <div class="col-md-8 no-padding" style="padding-right: 40px !important">
                    @include('sale_pos.partials.pos_sidebar')
                </div>
                @endif
            </div>
        </div>
    </div>
    <div style="display:none !important;">
    @include('sale_pos.partials.pos_form_actions')
    </div>
    {!! Form::close() !!}
</section>

<!-- This will be printed -->
<section class="invoice print_section" id="receipt_section"></section>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    @include('contact.create', ['quick_add' => true])
</div>
@if(empty($pos_settings['hide_product_suggestion']) && isMobile())
    @include('sale_pos.partials.mobile_product_suggestions')
@endif
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
<div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@include('sale_pos.partials.configure_search_modal')
@include('sale_pos.partials.recent_transactions_modal')
@include('sale_pos.partials.weighing_scale_modal')

@endsection

@section('javascript')











<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.0/purify.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/Amiri-normal.js') }}"></script>
<script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
@include('sale_pos.partials.keyboard_shortcuts')

<!-- Call restaurant module if defined -->
@if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    <script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
@endif

<!-- Include additional module JS if applicable -->
@if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
        @if(!empty($value['module_js_path']))
            @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
        @endif
    @endforeach
@endif
@endsection
