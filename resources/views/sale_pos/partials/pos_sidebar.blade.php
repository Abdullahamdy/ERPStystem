<div class="row" id="featured_products_box" style="display: none;">
@if(!empty($featured_products))
	@include('sale_pos.partials.featured_products')
@endif
</div>
<div class="row category-brand-carousel">
    <!-- Categories Carousel -->
    @if(!empty($categories))
        <div class="col-md-12" id="product_category_div">
            <!-- Hidden Dropdown for Categories -->
            <select class="select2" id="product_category" style="display: none !important;">
                <option value="all">@lang('lang_v1.all_category')</option>
                @foreach($categories as $category)
                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                    @if(!empty($category['sub_categories']))
                        <optgroup label="{{$category['name']}}">
                            @foreach($category['sub_categories'] as $sc)
                                <option value="{{$sc['id']}}">{{$sc['name']}}</option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            </select>

            <!-- Custom carousel-style category menu -->
            <div class="category-carousel">
                {{-- <div class="carousel-item category-item active" data-value="all">
                    <img src="https://img.icons8.com/ios-filled/50/000000/menu.png" alt="All Categories">
                    <div>@lang('lang_v1.all_category')</div>
                </div> --}}
                @foreach($categories as $category)
                    <div class="carousel-item category-item" data-value="{{ $category['id'] }}">
                        <img src="@if(!empty($category['image_url'])) 
                           {{ $category['image_url']}}
                                 @else 
                                    https://img.icons8.com/ios-filled/50/000000/user-male-circle.png 
                                 @endif" 
                        alt="{{ $category['name'] }}">
                        <div>{{ $category['name'] }}</div>
                    </div>

                    @if(!empty($category['sub_categories']))
                        @foreach($category['sub_categories'] as $sc)
                            <div class="carousel-item category-item" data-value="{{ $sc['id'] }}">
                                <img src="@if(!empty($sc['icon'])) 
                                            {{ asset('path/to/subcategory/icon/' . $sc['icon']) }} 
                                         @else 
                                            https://img.icons8.com/ios-filled/50/000000/user-male-circle.png 
                                         @endif" 
                                alt="{{ $sc['name'] }}">
                                <div>{{ $sc['name'] }}</div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    @endif

   
</div>


<br>
<div class="row">
	<input type="hidden" id="suggestion_page" value="1">
	<div class="col-md-12">
		<div class="eq-height-row" id="product_list_body"></div>
	</div>
	<div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
		<i class="fa fa-spinner fa-spin fa-2x"></i>
	</div>
</div>
@php
	$subtype = '';
@endphp
@if(!empty($transaction_sub_type))
	@php
		$subtype = '?sub_type='.$transaction_sub_type;
	@endphp
@endif

@if(!empty($transactions))
	<table class="table table-slim no-border">
		@foreach ($transactions as $transaction)
			<tr class="cursor-pointer" 
	    		title="Customer: {{optional($transaction->contact)->name}} 
		    		@if(!empty($transaction->contact->mobile) && $transaction->contact->is_default == 0)
		    			<br/>Mobile: {{$transaction->contact->mobile}}
		    		@endif
	    		" >
				<td>
					{{ $loop->iteration}}.
				</td>
				<td>
					{{ $transaction->invoice_no }} ({{optional($transaction->contact)->name}})
					@if(!empty($transaction->table))
						- {{$transaction->table->name}}
					@endif
				</td>
				<td class="display_currency">
					{{ $transaction->final_total }}
				</td>
				<td>
					@if(auth()->user()->can('sell.update') || auth()->user()->can('direct_sell.update'))
					<a href="{{action('SellPosController@edit', [$transaction->id]).$subtype}}">
	    				<i class="fas fa-pen text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_edit')}}"></i>
	    			</a>
	    			@endif
	    			@if(auth()->user()->can('sell.delete') || auth()->user()->can('direct_sell.delete'))
	    			<a href="{{action('SellPosController@destroy', [$transaction->id])}}" class="delete-sale" style="padding-left: 20px; padding-right: 20px"><i class="fa fa-trash text-danger" title="{{__('lang_v1.click_to_delete')}}"></i></a>
	    			@endif

	    			<a href="{{action('SellPosController@printInvoice', [$transaction->id])}}" class="print-invoice-link">
	    				<i class="fa fa-print text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_print')}}"></i>
	    			</a>
				</td>
			</tr>
		@endforeach
	</table>
@else
	<p>table 1</p>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Get references to category and brand menu items and dropdowns
    const categoryItems = document.querySelectorAll('.category-carousel .carousel-item');
    const brandItems = document.querySelectorAll('.brand-carousel .carousel-item');
    const categorySelect = document.getElementById('product_category');
    const brandSelect = document.getElementById('product_brand');
    const products = Array.from(document.querySelectorAll('.product-item'));

    let selectedCategoryId = 'all';
    let selectedBrandId = 'all';

    // Function to filter products based on selected category and brand
    function filterProducts() {
        let hasResults = false;

        products.forEach(product => {
            const productCategoryId = product.getAttribute('data-category-id');
            const productBrandId = product.getAttribute('data-brand-id');

            const categoryMatches = (selectedCategoryId === 'all' || productCategoryId === selectedCategoryId);
            const brandMatches = (selectedBrandId === 'all' || productBrandId === selectedBrandId);

            if (categoryMatches && brandMatches) {
                product.style.display = ''; // Show product
                hasResults = true;
            } else {
                product.style.display = 'none'; // Hide product
            }
        });

        // Handle "no products found" message
        const noProductsMessage = document.getElementById('no_products_found');
        if (!hasResults && noProductsMessage) {
            noProductsMessage.closest('.col-md-12').classList.remove('d-none');
        } else if (noProductsMessage) {
            noProductsMessage.closest('.col-md-12').classList.add('d-none');
        }
    }

    // Attach click events to category items
    categoryItems.forEach(category => {
        category.addEventListener('click', function () {
            selectedCategoryId = this.getAttribute('data-value');
            categorySelect.value = selectedCategoryId; // Update the hidden select value
            categorySelect.dispatchEvent(new Event('change')); // Trigger change event if required

            // Update active class for visual feedback
            categoryItems.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');

            filterProducts(); // Apply filtering
        });
    });

    // Attach click events to brand items
    brandItems.forEach(brand => {
        
        brand.addEventListener('click', function () {
            selectedBrandId = this.getAttribute('data-value');
            brandSelect.value = selectedBrandId; // Update the hidden select value
            brandSelect.dispatchEvent(new Event('change')); // Trigger change event if required

            // Update active class for visual feedback
            brandItems.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            filterProducts(); // Apply filtering
        });
    });

    // Set default state to show all products initially
    filterProducts();
});

</script>

<style>
    /* Carousel Styling */
.category-carousel, .brand-carousel {
    display: flex;
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
    gap: 15px;
}

.carousel-item {
    background-color: #ffffff;
    color: #333;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, box-shadow 0.3s;
    text-align: center;
    min-width: 100px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    flex: 0 0 auto; /* Prevent items from shrinking */
}

.carousel-item img {
    width: 40px;
    height: 40px;
    margin-bottom: 5px;
}
.select2 {
    display:none !important;
}
.carousel-item.active,
.carousel-item:hover {
    background-color: #3b82f6;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Hide the scrollbar for a cleaner look */
.category-carousel::-webkit-scrollbar,
.brand-carousel::-webkit-scrollbar {
    display: none;
}

</style>