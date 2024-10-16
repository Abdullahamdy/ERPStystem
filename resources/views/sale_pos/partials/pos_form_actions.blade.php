 
 <style>
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
        background-color: #00c853;
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
}

.icon-link:hover {
    color: #3b82f6;
}

.icon-link i {
    font-size: 1.5em;
}

.icon-link span {
    display: block;
    font-size: 0.8em;
    color: #333;
    margin-top: 5px;
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
 
 @php
        $is_mobile = isMobile();
    @endphp

    <div class="bottom-nav">
        <div class="icon-links">
            @if($is_mobile)
                <div class="icon-link total-payable">
                    <b>@lang('sale.total_payable'):</b>
                    <input type="hidden" name="final_total" id="final_total_input" value="0">
                    <span id="total_payable" class="text-success lead text-bold">0</span>
                </div>
            @endif

            <!-- Draft Button -->
            <div class="icon-link @if($pos_settings['disable_draft'] != 0) hide @endif">
                <button type="button" class="btn icon-btn" id="pos-draft">
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
            <div class="icon-link" @if(!array_key_exists('card', $payment_types)) style="display:none;" @endif>
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="card" title="@lang('lang_v1.tooltip_express_checkout_card')">
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
            <div class="icon-link" @if($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) style="display:none;" @endif>
                <button type="button" class="btn icon-btn no-print pos-express-finalize" data-pay_method="cash" title="@lang('tooltip.express_checkout')">
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

            @if(!$is_mobile)
                <div class="icon-link total-payable">
                    <span class="text">@lang('sale.total_payable')</span>
                    <input type="hidden" name="final_total" id="final_total_input" value="0">
                    <span id="total_payable" class="number">0</span>
                </div>
            @endif

				@if(!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
					<button type="button" class="btn btn-primary btn-flat sidebar-item" data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions">
						<i class="fas fa-clock"></i> @lang('lang_v1.recent_transactions')
					</button>
				@endif
			</div>
		</div>
	</div>
</div>

@if(isset($transaction))
	@include('sale_pos.partials.edit_discount_modal', [
		'sales_discount' => $transaction->discount_amount, 
		'discount_type' => $transaction->discount_type, 
		'rp_redeemed' => $transaction->rp_redeemed, 
		'rp_redeemed_amount' => $transaction->rp_redeemed_amount, 
		'max_available' => !empty($redeem_details['points']) ? $redeem_details['points'] : 0
	])
@else
	@include('sale_pos.partials.edit_discount_modal', [
		'sales_discount' => $business_details->default_sales_discount, 
		'discount_type' => 'percentage', 
		'rp_redeemed' => 0, 
		'rp_redeemed_amount' => 0, 
		'max_available' => 0
	])
@endif

@if(isset($transaction))
	@include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
	@include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $business_details->default_sales_tax])
@endif

@include('sale_pos.partials.edit_shipping_modal')

