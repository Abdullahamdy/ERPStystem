@extends('layouts.restaurant')
@section('title', __('restaurant.kitchen'))

@section('content')
<!-- Main content -->
<section class="content min-height-90hv no-print">

    <!-- Header Section -->
    <div class="filter-group mb-4">
        <button class="filter-btn">
            اسم العميل <i class="fas fa-chevron-down"></i>
        </button>
        <div style="position: relative;">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-bar" placeholder="بحث">
        </div>
        <div>
            <i class="fas fa-filter filter-icon"></i>
          <a href="https://srt-saudiroad.com/home">  <i class="fas fa-home home-icon"></i></a>
        </div>
    </div>

    <!-- Tabs for Orders Status -->
    <div class="tabs">
        <div class="tab active" data-status="new">جديد <span class="badge" id="new_count">0</span></div>
        <div class="tab" data-status="in_progress">في المطبخ <span class="badge" id="in_progress_count">0</span></div>
        <div class="tab" data-status="ready">تم <span class="badge" id="ready_count">0</span></div>
        <div class="tab" data-status="delayed">متأخر <span class="badge" id="delayed_count">0</span></div>
    </div>

    <!-- Order Cards Section -->
    <div class="box mt-4">
        <div class="box-header d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-sm btn-primary" id="refresh_orders">
                <i class="fas fa-sync"></i> @lang('restaurant.refresh')
            </button>
        </div>
        <div class="box-body">
            <input type="hidden" id="orders_for" value="kitchen">
            <div class="orders-container" id="orders_div">
                <!-- Orders will be dynamically injected here -->
                @include('restaurant.partials.show_orders', ['orders_for' => 'kitchen'])
            </div>
        </div>
        <div class="overlay hide">
            <i class="fas fa-sync fa-spin"></i>
        </div>
    </div>

</section>
<!-- /.content -->
@endsection

@section('javascript')
<script type="text/javascript">
$(document).ready(function () {
    let currentStatus = 'new';

    function loadOrders(status = 'new') {
        $.ajax({
            method: "GET",
            url: "{{ route('kitchen.orders') }}",
            data: { status: status },
            dataType: "json",
            success: function (result) {
                if (result.success == true) {
                    // Update the count for each tab
                    $('#new_count').text(result.counts.new);
                    $('#in_progress_count').text(result.counts.in_progress);
                    $('#ready_count').text(result.counts.ready);
                    $('#delayed_count').text(result.counts.delayed);

                    // Clear existing orders
                    $('#orders_div').empty();

                    // Dynamically create order cards
                    result.orders.forEach((order, index) => {
                        // Generate a class based on the order status
                        let orderClass = '';
                        if (order.status === 'new') {
                            orderClass = 'new';
                        } else if (order.status === 'in_progress') {
                            orderClass = 'in-progress';
                        } else if (order.status === 'ready') {
                            orderClass = 'ready';
                        } else if (order.status === 'delayed') {
                            orderClass = 'delayed';
                        }

                        const orderCard = `
                            <div class="order-card ${orderClass}">
                                <div class="order-header">
                                    <div class="order-info">
                                        <span class="order-number">#${order.order_number}</span>
                                        <span class="table-number">طاولة ${order.table_number}</span>
                                    </div>
                                    <div class="order-time">${order.time}</div>
                                </div>
                                <ul class="order-items">
                                    ${order.items.map(item => `
                                        <li class="order-item">
                                            ${item.name} x ${item.quantity}
                                        </li>
                                    `).join('')}
                                </ul>
                                <div class="order-footer">
                                    ${generateOrderButton(order.status, order.href)}
                                </div>
                            </div>
                        `;
                        $('#orders_div').append(orderCard);
                    });
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    }

    $(document).on('click', 'a.mark_as_cooked_btn', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        swal({
            title: LANG.sure,
            icon: "info",
            buttons: true,
        }).then((willUpdate) => {
            if (willUpdate) {
                $.ajax({
                    method: "GET",
                    url: href,
                    dataType: "json",
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            // Reload orders to reflect the changes in the current tab
                            loadOrders(currentStatus);
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });

    $('#refresh_orders').click(function () {
        loadOrders(currentStatus);
    });

    // Load orders on page load
    loadOrders();

    // Switch tabs
    $('.tab').click(function () {
        $('.tab').removeClass('active');
        $(this).addClass('active');
        currentStatus = $(this).data('status');
        loadOrders(currentStatus);
    });
});

</script>
@endsection

<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
    margin: 0;
    padding: 20px;
}

.content {
    max-width: 1500px;
    margin: 0 auto;
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Tabs for switching order status */
.tabs {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab {
    font-size: 16px;
    color: #333;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-bottom: 10px;
}

.tab.active {
    background-color: #5e72e4;
    color: #fff;
}

.badge {
    background: #fff;
    color: #333;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 12px;
    margin-left: 5px;
}

/* Orders container */
.orders-container {
    display: flex;
    gap: 20px;
    justify-content: space-between;
    flex-wrap: wrap;
}

/* Order card styling */
.order-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    width: 100%;
    max-width: 23%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-left: 8px solid;
    position: relative;
}

.order-card.new {
    border-color: #f5365c;
}

.order-card.in-progress {
    border-color: #11cdef;
}

.order-card.ready {
    border-color: #2dce89;
}

.order-card.delayed {
    border-color: #f39c12;
}

/* Order header and details */
.order-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 1.2em;
}

.order-details {
    font-size: 1em;
    color: #666;
    margin-top: 10px;
}

.order-items {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px dashed #e0e0e0;
}

.order-footer {
    text-align: center;
    margin-top: 10px;
}

.order-footer button {
    background-color: #5e72e4;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.order-footer button:hover {
    background-color: #324cdd;
}

/* Filters, search, and header */
.filter-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-btn {
    font-size: 16px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background-color: #ffffff;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter-btn:hover {
    background-color: #f0f0f0;
}

.search-bar-wrapper {
    position: relative;
    width: 100%;
    max-width: 300px;
    margin-bottom: 10px;
}

.search-bar {
    font-size: 16px;
    padding: 10px 15px 10px 40px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background-color: #ffffff;
    width: 100%;
}

.search-icon {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    color: #666;
    font-size: 1.2em;
}

.filter-icon,
.home-icon {
    font-size: 1.8em;
    color: #666;
    cursor: pointer;
    transition: color 0.3s;
    margin-left: 15px;
}

.filter-icon:hover,
.home-icon:hover {
    color: #5e72e4;
}

/* Responsive styling */
@media (max-width: 1024px) {
    .order-card {
        max-width: 45%;
    }

    .tab {
        flex-grow: 1;
        text-align: center;
        margin-bottom: 5px;
    }
}

@media (max-width: 768px) {
    .filter-group {
        flex-direction: column;
        gap: 10px;
    }

    .order-card {
        max-width: 100%;
    }

    .tabs {
        flex-direction: column;
        align-items: stretch;
    }

    .tab {
        padding: 10px;
        text-align: center;
    }

    .search-bar-wrapper {
        width: 100%;
    }
}
.lockscreen {
     background: #fff !important; 
}
@media (max-width: 480px) {
    .filter-group {
        align-items: stretch;
    }

    .filter-btn {
        width: 100%;
    }

    .search-bar {
        padding-left: 35px;
    }

    .filter-icon,
    .home-icon {
        margin-left: 0;
        margin-right: 10px;
    }
}
.row .no-print {
    display:none !impor;
}
.pull-right {
    float: left !important;
    display: none  !important;
}
</style>
