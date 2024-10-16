@forelse($orders as $order)
	<div class="col-md-3 col-xs-6 order_div">
		<div class="small-box bg-gray">
            <div class="inner">
            	<h4 class="text-center">#{{$order->invoice_no}}</h4>
            	<table class="table no-margin no-border table-slim">
            		<tr><th>@lang('restaurant.placed_at')</th><td>{{@format_date($order->created_at)}} {{ @format_time($order->created_at)}}</td></tr>
            		<tr><th>@lang('restaurant.order_status')</th>
                              @php
                                    $count_sell_line = count($order->sell_lines);
                                    $count_cooked = count($order->sell_lines->where('res_line_order_status', 'cooked'));
                                    $count_served = count($order->sell_lines->where('res_line_order_status', 'served'));
                                    $order_status =  'received';
                                    if($count_cooked == $count_sell_line) {
                                          $order_status =  'cooked';
                                    } else if($count_served == $count_sell_line) {
                                          $order_status =  'served';
                                    } else if ($count_served > 0 && $count_served < $count_sell_line) {
                                          $order_status =  'partial_served';
                                    } else if ($count_cooked > 0 && $count_cooked < $count_sell_line) {
                                          $order_status =  'partial_cooked';
                                    }
                                    
                              @endphp
                              <td><span class="label @if($order_status == 'cooked' ) bg-red @elseif($order_status == 'served') bg-green @elseif($order_status == 'partial_cooked') bg-orange @else bg-light-blue @endif">@lang('restaurant.order_statuses.' . $order_status) </span></td>
                        </tr>
            		<tr><th>@lang('contact.customer')</th><td>{{$order->customer_name}}</td></tr>
            		<tr><th>@lang('restaurant.table')</th><td>{{$order->table_name}}</td></tr>
            		<tr><th>@lang('sale.location')</th><td>{{$order->business_location}}</td></tr>
            	</table>
            </div>
            @if($orders_for == 'kitchen')
            	<a href="#" class="btn btn-flat small-box-footer bg-yellow mark_as_cooked_btn" data-href="{{action('Restaurant\KitchenController@markAsCooked', [$order->id])}}"><i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_cooked')</a>
            @elseif($orders_for == 'waiter' && $order->res_order_status != 'served')
            	<a href="#" class="btn btn-flat small-box-footer bg-yellow mark_as_served_btn" data-href="{{action('Restaurant\OrderController@markAsServed', [$order->id])}}"><i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_served')</a>
            @else
            	<div class="small-box-footer bg-gray">&nbsp;</div>
            @endif
            	<a href="#" class="btn btn-flat small-box-footer bg-info btn-modal" data-href="{{ action('SellController@show', [$order->id])}}" data-container=".view_modal">@lang('restaurant.order_details') <i class="fa fa-arrow-circle-right"></i></a>
         </div>
	</div>
	@if($loop->iteration % 4 == 0)
		<div class="hidden-xs">
			<div class="clearfix"></div>
		</div>
	@endif
	@if($loop->iteration % 2 == 0)
		<div class="visible-xs">
			<div class="clearfix"></div>
		</div>
	@endif
@empty
<div class="col-md-12">
	<h4 class="text-center">@lang('restaurant.no_orders_found')</h4>
</div>
@endforelse 


<style>
    /* General Styles for Order Cards */
.order_div {
    padding: 15px;
}

.small-box {
    border-radius: 10px;
    position: relative;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.small-box .inner {
    padding: 15px;
    color: #333;
}

.small-box h4 {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.small-box table {
    width: 100%;
    font-size: 0.9rem;
}

.table.no-margin {
    border: none;
    margin-bottom: 0;
}

.table.no-border td {
    border: none !important;
}

/* Order Status Styles */
.small-box-footer {
    display: block;
    padding: 10px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-radius: 0 0 10px 10px;
}

.bg-yellow {
    background-color: #FFC107 !important;
}

.bg-info {
    background-color: #17A2B8 !important;
}

.bg-gray {
    background-color: #f8f9fa !important;
    color: #495057 !important;
}

/* Status Colors */
.bg-red {
    background-color: #FF5252 !important;
    color: #fff !important;
}

.bg-green {
    background-color: #2DCE89 !important;
    color: #fff !important;
}

.bg-orange {
    background-color: #FFA726 !important;
    color: #fff !important;
}

.bg-light-blue {
    background-color: #42A5F5 !important;
    color: #fff !important;
}

/* Button Styles for 'Mark as Cooked/Served' */
.btn-flat {
    border-radius: 5px;
    margin-top: 10px;
}

.btn-modal {
    margin-top: 10px;
}

@media (min-width: 768px) {
    .order_div {
        width: 23%;
    }
}

@media (max-width: 767px) {
    .order_div {
        width: 48%;
    }
}

@media (max-width: 480px) {
    .order_div {
        width: 100%;
    }
}

/* Tabs Section */
.tabs {
    display: flex;
    justify-content: space-around;
    margin-bottom: 20px;
}

.tab {
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    background-color: #f1f1f1;
    color: #333;
    font-weight: bold;
}

.tab.active {
    background-color: #5e72e4;
    color: #fff;
}

.badge {
    background: #ffffff;
    color: #333;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 20px;
    margin-left: 5px;
}

/* Search and Filter Icons */
.filter-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-btn,
.search-bar {
    font-size: 16px;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background-color: #fff;
    cursor: pointer;
}

.search-bar {
    width: 300px;
    padding-left: 40px;
}

.search-bar-wrapper {
    position: relative;
    width: 100%;
    max-width: 300px;
}


.search-icon {
    left: 10px;
}

.filter-icon {
    right: 60px;
}

.home-icon {
    right: 20px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-btn,
    .search-bar-wrapper {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .tabs {
        flex-direction: column;
    }

    .tab {
        margin-bottom: 5px;
        text-align: center;
    }
}

</style>