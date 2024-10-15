@extends('layouts.app')
@section('title', __('home.home'))
<style>
    <style>
    /* General styling for the row and column */
    .row-custom {
        margin-bottom: 15px;
    }

    .col-custom {
        padding: 10px;
    }

    /* Styling for the rectangular widget-like cards */
    .card-widget {
        display: flex;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
    }

    .info-box-icon {
        width: 60px; /* Fixed width for icon area */
        height: 60px;
        display: flex;
        align-items: center;
        background: none !important;
        justify-content: center;
        color: #fff; /* White color for icons */
        font-size: 30px;
     line-height: 60px !important;
    }
    .skin-blue .content-wrapper .content-header-custom, .skin-blue-light .content-wrapper .content-header-custom {
    background: #fefefe !important;
    color: #f6f7fc !important;
    padding: 15px 15px 200px;
}
.row-custom{
background: #fefefe !important;
    

}
 .content-wrapper {
background: #fefefe !important;
     
 }
    .info-box-content {
        flex: 1; /* Take the remaining width */
        padding-left: 15px; /* Space between icon and text */
    }

    .info-box-text {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .info-box-number {
        font-size: 20px;
        color: #666;
    }

    /* Background colors for different cards */
    .bg-aqua {
        background-color: #00c0ef !important; /* Aqua blue */
    }

    .bg-green {
        background-color: #00a65a !important; /* Green */
    }

    .bg-yellow {
        background-color: #f39c12 !important; /* Yellow */
    }

    .bg-red {
        background-color: #dd4b39 !important; /* Red */
    }

    /* Icon styling for better alignment and size */
    .info-box-icon i {
        margin: 0 auto;
    }

    /* No border radius to match the rectangular card style */
    .card-widget {
        border-radius: 4px; /* Minimal rounding to maintain a sharp look */
    }
    .box.box-warning {
    border-top-color: #3c8dbc !important;
}
.text-warning, .text-yellow {
    color: #3c8dbc !important;
}
.fas .fa-info-circle {
    display:none !important;
}
</style>

</style>
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header content-header-custom">
    <h1>{{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
    </h1>
</section>
<!-- Main content -->
<section class="content content-custom no-print">
    <br>
    @if(auth()->user()->can('dashboard.data'))
        @if($is_admin)
        	<div class="row">
                <div class="col-md-4 col-xs-12">
                    @if(count($all_locations) > 1)
                        {!! Form::select('dashboard_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'dashboard_location']); !!}
                    @endif
                </div>
        		<div class="col-md-8 col-xs-12">
        		</div>
        	</div>
    	   <br>
    	  <div class="row row-custom">
    <!-- Total Sell Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget"  style="background-color:#11cdef !important ; color:white !important;">
            <div class="info-box-icon bg-aqua text-center">
                <i class="ion ion-ios-cart-outline"></i>
            </div>
            <div class="info-box-content" style="color:#fff !important;">
                <span class="info-box-text" style="color:#fff !important;">{{ __('home.total_sell') }}</span>
                <span class="info-box-number total_sell" style="color:#fff !important;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Net Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color:#2dce89 !important ; color:white !important;">
            <div class="info-box-icon bg-green text-center" >
                <i class="ion ion-ios-paper-outline"></i>
            </div>
            <div class="info-box-content" style="color:#fff !important;">
                <span class="info-box-text" style="color:#fff !important;">{{ __('lang_v1.net') }}</span>
                <span class="info-box-number net" style="color:#fff !important;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Invoice Due Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget"  style="background-color:#ffad46 !important ; color:white !important;">
            <div class="info-box-icon bg-yellow text-center">
                <i class="ion ion-ios-paper-outline"></i>
                <i class="fa fa-exclamation"></i>
            </div>
            <div class="info-box-content" style="color:#fff !important;">
                <span class="info-box-text" style="color:#fff !important;">{{ __('home.invoice_due') }}</span>
                <span class="info-box-number invoice_due" style="color:#fff !important;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Total Sell Return Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color:#f5365c !important ; color:white !important;">
            <div class="info-box-icon bg-red text-center" >
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text" style="color:#fff !important;">{{ __('lang_v1.total_sell_return') }}</span>
                <span class="info-box-number total_sell_return" style="color:#fff !important;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row row-custom">
    <!-- Total Purchase Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color: #11cdef; color: white;">
            <div class="info-box-icon">
                <i class="ion ion-cash"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text" style="color: white;">{{ __('home.total_purchase') }}</span>
                <span class="info-box-number total_purchase" style="color: white;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Purchase Due Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color: #ffad46; color: white;">
            <div class="info-box-icon">
                <i class="fa fa-dollar"></i>
                <i class="fa fa-exclamation"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text" style="color: white;">{{ __('home.purchase_due') }}</span>
                <span class="info-box-number purchase_due" style="color: white;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Total Purchase Return Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color: #f5365c; color: white;">
            <div class="info-box-icon">
                <i class="fas fa-undo-alt"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text" style="color: white;">{{ __('lang_v1.total_purchase_return') }}</span>
                <span class="info-box-number total_purchase_return" style="color: white;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>

    <!-- Expense Card -->
    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
        <div class="info-box card-widget" style="background-color: #f5365c; color: white;">
            <div class="info-box-icon">
                <i class="fas fa-minus-circle"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text" style="color: white;">{{ __('lang_v1.expense') }}</span>
                <span class="info-box-number total_expense" style="color: white;"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
            </div>
        </div>
    </div>
</div>

            @if(!empty($widgets['after_sale_purchase_totals']))
                @foreach($widgets['after_sale_purchase_totals'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
        @endif 
        <!-- end is_admin check -->
         @if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
            @if(!empty($all_locations))
              	<!-- sales chart start -->
              	<div class="row">
              		<div class="col-sm-6">
                        @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_last_30_days')])
                          {!! $sells_chart_1->container() !!}
                        @endcomponent
              		</div>
              	
            @endif
            @if(!empty($widgets['after_sales_last_30_days']))
                @foreach($widgets['after_sales_last_30_days'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
            @if(!empty($all_locations))
              	
              		<div class="col-sm-6">
                        @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_current_fy')])
                          {!! $sells_chart_2->container() !!}
                        @endcomponent
              		</div>
              	</div>
            @endif
        @endif
      	<!-- sales chart end -->
        @if(!empty($widgets['after_sales_current_fy']))
            @foreach($widgets['after_sales_current_fy'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
      	<!-- products less than alert quntity -->
      	<div class="row">
            @if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                      @slot('icon')
                        <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                      @endslot
                      @slot('title')
                        {{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))
                      @endslot
                        <div class="row">
                            @if(count($all_locations) > 1)
                                <div class="col-md-6 col-sm-6 col-md-offset-6 mb-10">
                                    {!! Form::select('sales_payment_dues_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'sales_payment_dues_location']); !!}
                                </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="sales_payment_dues_table" style="width: 100%;">
                                    <thead>
                                      <tr>
                                        <th>@lang( 'contact.customer' )</th>
                                        <th>@lang( 'sale.invoice_no' )</th>
                                        <th>@lang( 'home.due_amount' )</th>
                                        <th>@lang( 'messages.action' )</th>
                                      </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endcomponent
                </div>
            @endif
            @can('purchase.view')
                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                    @slot('icon')
                    <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                    @endslot
                    @slot('title')
                    {{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))
                    @endslot
                    <div class="row">
                        @if(count($all_locations) > 1)
                            <div class="col-md-6 col-sm-6 col-md-offset-6 mb-10">
                                {!! Form::select('purchase_payment_dues_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'purchase_payment_dues_location']); !!}
                            </div>
                        @endif
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped" id="purchase_payment_dues_table" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th>@lang( 'purchase.supplier' )</th>
                                    <th>@lang( 'purchase.ref_no' )</th>
                                    <th>@lang( 'home.due_amount' )</th>
                                    <th>@lang( 'messages.action' )</th>
                                  </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    @endcomponent
                </div>
            @endcan
        </div>
        @can('stock_report.view')
            <div class="row">
                <div class="@if((session('business.enable_product_expiry') != 1) && auth()->user()->can('stock_report.view')) col-sm-6 @else col-sm-6 @endif">
                    @component('components.widget', ['class' => 'box-warning'])
                      @slot('icon')
                        <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                      @endslot
                      @slot('title')
                        {{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))
                      @endslot
                      <div class="row">
                            @if(count($all_locations) > 1)
                                <div class="col-md-12 col-sm-6 col-md-offset-6 mb-10">
                                    {!! Form::select('stock_alert_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'stock_alert_location']); !!}
                                </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="stock_alert_table" style="width: 100%;">
                                    <thead>
                                      <tr>
                                        <th>@lang( 'sale.product' )</th>
                                        <th>@lang( 'business.location' )</th>
                                        <th>@lang( 'report.current_stock' )</th>
                                      </tr>
                                    </thead>
                                </table>
                            </div>
                      </div>
                    @endcomponent
                </div>
                @if(session('business.enable_product_expiry') == 1)
                    <div class="col-sm-6">
                        @component('components.widget', ['class' => 'box-warning'])
                          @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                          @endslot
                          @slot('title')
                            {{ __('home.stock_expiry_alert') }} @show_tooltip( __('tooltip.stock_expiry_alert', [ 'days' =>session('business.stock_expiry_alert_days', 30) ]) )
                          @endslot
                          <input type="hidden" id="stock_expiry_alert_days" value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
                          <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
                            <thead>
                              <tr>
                                  <th>@lang('business.product')</th>
                                  <th>@lang('business.location')</th>
                                  <th>@lang('report.stock_left')</th>
                                  <th>@lang('product.expires_in')</th>
                              </tr>
                            </thead>
                          </table>
                        @endcomponent
                    </div>
                @endif
      	    </div>
        @endcan
        @if(auth()->user()->can('so.view_all') || auth()->user()->can('so.view_own'))
            <div class="row" @if(!auth()->user()->can('dashboard.data'))style="margin-top: 190px !important;"@endif>
                <div class="col-sm-12">
                    @component('components.widget', ['class' => 'box-warning'])
                        @slot('icon')
                            <i class="fas fa-list-alt text-yellow fa-lg" aria-hidden="true"></i>
                        @endslot
                        @slot('title')
                            {{__('lang_v1.sales_order')}}
                        @endslot
                        <div class="row">
                        @if(count($all_locations) > 1)
                            <div class="col-md-4 col-sm-12 col-md-offset-8 mb-10">
                                {!! Form::select('so_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'so_location']); !!}
                            </div>
                        @endif
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ajax_view" id="sales_order_table">
                                        <thead>
                                            <tr>
                                                <th>@lang('messages.action')</th>
                                                <th>@lang('messages.date')</th>
                                                <th>@lang('restaurant.order_no')</th>
                                                <th>@lang('sale.customer_name')</th>
                                                <th>@lang('lang_v1.contact_no')</th>
                                                <th>@lang('sale.location')</th>
                                                <th>@lang('sale.status')</th>
                                                <th>@lang('lang_v1.shipping_status')</th>
                                                <th>@lang('lang_v1.quantity_remaining')</th>
                                                <th>@lang('lang_v1.added_by')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>
        @endif
        @if(!empty($common_settings['enable_purchase_order']) && (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own')) )
            <div class="row" @if(!auth()->user()->can('dashboard.data'))style="margin-top: 190px !important;"@endif>
                <div class="col-sm-12">
                    @component('components.widget', ['class' => 'box-warning'])
                      @slot('icon')
                          <i class="fas fa-list-alt text-yellow fa-lg" aria-hidden="true"></i>
                      @endslot
                      @slot('title')
                          @lang('lang_v1.purchase_order')
                      @endslot
                        <div class="row">
                        @if(count($all_locations) > 1)
                            <div class="col-md-4 col-sm-12 col-md-offset-8 mb-10">
                                {!! Form::select('po_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'po_location']); !!}
                            </div>
                        @endif
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped ajax_view" id="purchase_order_table" style="width: 100%;">
                                      <thead>
                                          <tr>
                                              <th>@lang('messages.action')</th>
                                              <th>@lang('messages.date')</th>
                                              <th>@lang('purchase.ref_no')</th>
                                              <th>@lang('purchase.location')</th>
                                              <th>@lang('purchase.supplier')</th>
                                              <th>@lang('sale.status')</th>
                                              <th>@lang('lang_v1.quantity_remaining')</th>
                                              <th>@lang('lang_v1.added_by')</th>
                                          </tr>
                                      </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>
        @endif

        @if(auth()->user()->can('access_pending_shipments_only') || auth()->user()->can('access_shipping') || auth()->user()->can('access_own_shipping') )
            @component('components.widget', ['class' => 'box-warning'])
              @slot('icon')
                  <i class="fas fa-list-alt text-yellow fa-lg" aria-hidden="true"></i>
              @endslot
              @slot('title')
                  @lang('lang_v1.pending_shipments')
              @endslot
                <div class="row">
                    @if(count($all_locations) > 1)
                        <div class="col-md-4 col-sm-6 col-md-offset-8 mb-10">
                            {!! Form::select('pending_shipments_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'pending_shipments_location']); !!}
                        </div>
                    @endif
                    <div class="col-md-12">  
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ajax_view" id="shipments_table">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.action')</th>
                                        <th>@lang('messages.date')</th>
                                        <th>@lang('sale.invoice_no')</th>
                                        <th>@lang('sale.customer_name')</th>
                                        <th>@lang('lang_v1.contact_no')</th>
                                        <th>@lang('sale.location')</th>
                                        <th>@lang('lang_v1.shipping_status')</th>
                                        @if(!empty($custom_labels['shipping']['custom_field_1']))
                                            <th>
                                                {{$custom_labels['shipping']['custom_field_1']}}
                                            </th>
                                        @endif
                                        @if(!empty($custom_labels['shipping']['custom_field_2']))
                                            <th>
                                                {{$custom_labels['shipping']['custom_field_2']}}
                                            </th>
                                        @endif
                                        @if(!empty($custom_labels['shipping']['custom_field_3']))
                                            <th>
                                                {{$custom_labels['shipping']['custom_field_3']}}
                                            </th>
                                        @endif
                                        @if(!empty($custom_labels['shipping']['custom_field_4']))
                                            <th>
                                                {{$custom_labels['shipping']['custom_field_4']}}
                                            </th>
                                        @endif
                                        @if(!empty($custom_labels['shipping']['custom_field_5']))
                                            <th>
                                                {{$custom_labels['shipping']['custom_field_5']}}
                                            </th>
                                        @endif
                                        <th>@lang('sale.payment_status')</th>
                                        <th>@lang('restaurant.service_staff')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> 
                </div>
            @endcomponent
        @endif

        @if(auth()->user()->can('account.access') && config('constants.show_payments_recovered_today') == true)
            @component('components.widget', ['class' => 'box-warning'])
              @slot('icon')
                  <i class="fas fa-money-bill-alt text-yellow fa-lg" aria-hidden="true"></i>
              @endslot
              @slot('title')
                  @lang('lang_v1.payment_recovered_today')
              @endslot
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="cash_flow_table">
                        <thead>
                            <tr>
                                <th>@lang( 'messages.date' )</th>
                                <th>@lang( 'account.account' )</th>
                                <th>@lang( 'lang_v1.description' )</th>
                                <th>@lang( 'lang_v1.payment_method' )</th>
                                <th>@lang( 'lang_v1.payment_details' )</th>
                                <th>@lang('account.credit')</th>
                                <th>@lang( 'lang_v1.account_balance' ) @show_tooltip(__('lang_v1.account_balance_tooltip'))</th>
                                <th>@lang( 'lang_v1.total_balance' ) @show_tooltip(__('lang_v1.total_balance_tooltip'))</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 footer-total text-center">
                                <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                <td class="footer_total_credit"></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcomponent
        @endif

        @if(!empty($widgets['after_dashboard_reports']))
          @foreach($widgets['after_dashboard_reports'] as $widget)
            {!! $widget !!}
          @endforeach
        @endif

    @endif
   <!-- can('dashboard.data') end -->
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
@stop
@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    @includeIf('sales_order.common_js')
    @includeIf('purchase_order.common_js')
    @if(!empty($all_locations))
        {!! $sells_chart_1->script() !!}
        {!! $sells_chart_2->script() !!}
    @endif
    <script type="text/javascript">
        $(document).ready( function(){
        sales_order_table = $('#sales_order_table').DataTable({
          processing: true,
          serverSide: true,
          scrollY: "75vh",
          scrollX:        true,
          scrollCollapse: true,
          aaSorting: [[1, 'desc']],
          "ajax": {
              "url": '{{action("SellController@index")}}?sale_type=sales_order',
              "data": function ( d ) {
                    d.for_dashboard_sales_order = true;

                    if ($('#so_location').length > 0) {
                        d.location_id = $('#so_location').val();
                    }
                }
          },
          columnDefs: [ {
              "targets": 7,
              "orderable": false,
              "searchable": false
          } ],
          columns: [
              { data: 'action', name: 'action'},
              { data: 'transaction_date', name: 'transaction_date'  },
              { data: 'invoice_no', name: 'invoice_no'},
              { data: 'conatct_name', name: 'conatct_name'},
              { data: 'mobile', name: 'contacts.mobile'},
              { data: 'business_location', name: 'bl.name'},
              { data: 'status', name: 'status'},
              { data: 'shipping_status', name: 'shipping_status'},
              { data: 'so_qty_remaining', name: 'so_qty_remaining', "searchable": false},
              { data: 'added_by', name: 'u.first_name'},
          ]
        });

        @if(auth()->user()->can('account.access') && config('constants.show_payments_recovered_today') == true)

            // Cash Flow Table
            cash_flow_table = $('#cash_flow_table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                        "url": "{{action("AccountController@cashFlow")}}",
                        "data": function ( d ) {
                            d.type = 'credit';
                            d.only_payment_recovered = true;
                        }
                    },
                "ordering": false,
                "searching": false,
                columns: [
                    {data: 'operation_date', name: 'operation_date'},
                    {data: 'account_name', name: 'account_name'},
                    {data: 'sub_type', name: 'sub_type'},
                    {data: 'method', name: 'TP.method'},
                    {data: 'payment_details', name: 'payment_details', searchable: false},
                    {data: 'credit', name: 'amount'},
                    {data: 'balance', name: 'balance'},
                    {data: 'total_balance', name: 'total_balance'},
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#cash_flow_table'));
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var footer_total_credit = 0;

                    for (var r in data){
                        footer_total_credit += $(data[r].credit).data('orig-value') ? parseFloat($(data[r].credit).data('orig-value')) : 0;
                    }
                    $('.footer_total_credit').html(__currency_trans_from_en(footer_total_credit));
                }
            });
        @endif

        $('#so_location').change( function(){
            sales_order_table.ajax.reload();
        });
        @if(!empty($common_settings['enable_purchase_order']))
          //Purchase table
          purchase_order_table = $('#purchase_order_table').DataTable({
              processing: true,
              serverSide: true,
              aaSorting: [[1, 'desc']],
              scrollY: "75vh",
              scrollX:        true,
              scrollCollapse: true,
              ajax: {
                  url: '{{action("PurchaseOrderController@index")}}',
                  data: function(d) {
                      d.from_dashboard = true;

                        if ($('#po_location').length > 0) {
                            d.location_id = $('#po_location').val();
                        }
                  },
              },
              columns: [
                  { data: 'action', name: 'action', orderable: false, searchable: false },
                  { data: 'transaction_date', name: 'transaction_date' },
                  { data: 'ref_no', name: 'ref_no' },
                  { data: 'location_name', name: 'BS.name' },
                  { data: 'name', name: 'contacts.name' },
                  { data: 'status', name: 'transactions.status' },
                  { data: 'po_qty_remaining', name: 'po_qty_remaining', "searchable": false},
                  { data: 'added_by', name: 'u.first_name' }
              ]
            })

            $('#po_location').change( function(){
                purchase_order_table.ajax.reload();
            });
        @endif

        sell_table = $('#shipments_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[1, 'desc']],
            scrollY:        "75vh",
            scrollX:        true,
            scrollCollapse: true,
            "ajax": {
                "url": '{{action("SellController@index")}}',
                "data": function ( d ) {
                    d.only_pending_shipments = true;
                    if ($('#pending_shipments_location').length > 0) {
                        d.location_id = $('#pending_shipments_location').val();
                    }
                }
            },
            columns: [
                { data: 'action', name: 'action', searchable: false, orderable: false},
                { data: 'transaction_date', name: 'transaction_date'  },
                { data: 'invoice_no', name: 'invoice_no'},
                { data: 'conatct_name', name: 'conatct_name'},
                { data: 'mobile', name: 'contacts.mobile'},
                { data: 'business_location', name: 'bl.name'},
                { data: 'shipping_status', name: 'shipping_status'},
                @if(!empty($custom_labels['shipping']['custom_field_1']))
                    { data: 'shipping_custom_field_1', name: 'shipping_custom_field_1'},
                @endif
                @if(!empty($custom_labels['shipping']['custom_field_2']))
                    { data: 'shipping_custom_field_2', name: 'shipping_custom_field_2'},
                @endif
                @if(!empty($custom_labels['shipping']['custom_field_3']))
                    { data: 'shipping_custom_field_3', name: 'shipping_custom_field_3'},
                @endif
                @if(!empty($custom_labels['shipping']['custom_field_4']))
                    { data: 'shipping_custom_field_4', name: 'shipping_custom_field_4'},
                @endif
                @if(!empty($custom_labels['shipping']['custom_field_5']))
                    { data: 'shipping_custom_field_5', name: 'shipping_custom_field_5'},
                @endif
                { data: 'payment_status', name: 'payment_status'},
                { data: 'waiter', name: 'ss.first_name', @if(empty($is_service_staff_enabled)) visible: false @endif }
            ],
            "fnDrawCallback": function (oSettings) {
                __currency_convert_recursively($('#sell_table'));
            },
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(4)').attr('class', 'clickable_td');
            }
        });

        $('#pending_shipments_location').change( function(){
            sell_table.ajax.reload();
        });
    });
    </script>
@endsection

