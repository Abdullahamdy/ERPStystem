@section('css')
<style>
    /* General Styling to Match Product Grid Section */
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f7fa;
        margin: 0;
        padding: 0;
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
        background-color: #3b82f6;
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

    /* Search Bar Styling */
    .search-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .search-bar {
        width: 100%;
        max-width: 500px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 25px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        outline: none;
    }
</style>
@endsection

@section('content')
<section class="content no-print pos-container">
    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="search_product" class="search-bar" placeholder="اسم المنتج">
    </div>

    <!-- Product Grid -->
 <div class="product-grid" id="product_grid">
    @forelse($products as $product)
  
        <div class="product_box product-item" 
             data-category-id="{{$product->category_id}}" 
             data-brand-id="{{$product->brand_id}}" 
             data-variation_id="{{$product->id}}" 
             title="{{$product->name}}" 
             data-name="{{ strtolower($product->name) }}" 
             data-available="{{ $product->enable_stock ? 'true' : 'false' }}">
             
            <!-- Price Badge -->
            <div class="price-badge" >
                <input type="hidden" class="updatefortotal_price" value="{{ number_format($product->selling_price, 2) }}">
                {{ number_format($product->selling_price, 2) }}$
            </div>

            <!-- Product Image -->
            <div class="image-container"
                 style="background-image: url(
                 @if(count($product->media) > 0)
                    {{$product->media->first()->display_url}}
                 @elseif(!empty($product->product_image))
                    {{asset('/uploads/img/' . rawurlencode($product->product_image))}}
                 @else
                    {{asset('/img/default.png')}}
                 @endif
                 );">
            </div>

            <!-- Product Details -->
            <div class="text_div">
                <div class="product-name">
                    {{$product->name}}
                    @if($product->type == 'variable')
                        - {{$product->variation}}
                    @endif
                </div>
                <div class="product-details">
                    <span>{{ $product->enable_stock ? __('Available') : __('Not Available') }}</span>
                </div>
            </div>
        </div>
    @empty
        <input type="hidden" id="no_products_found">
        <div class="col-md-12">
            <h4 class="text-center">
                @lang('lang_v1.no_products_to_display')
            </h4>
        </div>
    @endforelse
</div>

</section> 

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBar = document.getElementById('search_product');
    const productGrid = document.getElementById('product_grid');
    const products = Array.from(productGrid.querySelectorAll('.product-item'));

    // Search functionality for filtering products
    searchBar.addEventListener('input', function() {
        const searchText = this.value.trim().toLowerCase();
        let hasResults = false;

        products.forEach(product => {
            const productName = product.getAttribute('data-name');
            if (productName.includes(searchText)) {
                product.style.display = 'block';
                hasResults = true;
            } else {
                product.style.display = 'none';
            }
        });

        // Handle no results found case
        const noProductsMessage = document.getElementById('no_products_found');
        if (!hasResults) {
            if (noProductsMessage) {
                noProductsMessage.closest('.col-md-12').classList.remove('d-none');
            }
        } else {
            if (noProductsMessage) {
                noProductsMessage.closest('.col-md-12').classList.add('d-none');
            }
        }
    });

    // Click event to handle product selection
    products.forEach(product => {
        product.addEventListener('click', function() {
            const isAvailable = this.getAttribute('data-available') === 'true';

            if (isAvailable) {
                // Add product to form, e.g., shopping cart or order form
                alert('Product added: ' + this.getAttribute('title'));
                // Add your logic to add the product to the cart here.
            } else {
                alert('This product is not available.');
            }
        });
    });
});



</script>
@endsection
