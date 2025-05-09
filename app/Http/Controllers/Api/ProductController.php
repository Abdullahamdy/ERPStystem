<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    public function getProducts()
    {
        if (!auth()->user()->can('product.view') && !auth()->user()->can('product.create')) {
            return $this->respondError('Unauthorized action.', 403);
        }
    
        $business_id = auth()->user()->business_id;
        
        // Filter by location
        $location_id = request()->get('location_id', null);
        $permitted_locations = auth()->user()->permitted_locations();
    
        $query = Product::with(['media', 'product_locations', 'store', 'printer'])
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
            ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
            ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
            ->join('variations as v', 'v.product_id', '=', 'products.id')
            ->leftJoin('variation_location_details as vld', function ($join) use ($permitted_locations) {
                $join->on('vld.variation_id', '=', 'v.id');
                if ($permitted_locations != 'all') {
                    $join->whereIn('vld.location_id', $permitted_locations);
                }
            })
            ->whereNull('v.deleted_at')
            ->where('products.business_id', $business_id)
            ->where('products.type', '!=', 'modifier');
    
        // Apply location filter
        if (!empty($location_id) && $location_id != 'none') {
            if ($permitted_locations == 'all' || in_array($location_id, $permitted_locations)) {
                $query->whereHas('product_locations', function ($query) use ($location_id) {
                    $query->where('product_locations.location_id', '=', $location_id);
                });
            }
        } elseif ($location_id == 'none') {
            $query->doesntHave('product_locations');
        } else {
            if ($permitted_locations != 'all') {
                $query->whereHas('product_locations', function ($query) use ($permitted_locations) {
                    $query->whereIn('product_locations.location_id', $permitted_locations);
                });
            }
        }
    
        $products = $query->select(
            'products.id',
            'products.name as product',
            'products.store_id',
            'products.type',
            'products.printer_id',
            'c1.name as category',
            'c2.name as sub_category',
            'units.actual_name as unit',
            'brands.name as brand',
            'tax_rates.name as tax',
            'products.sku',
            'products.image',
            'products.enable_stock',
            'products.is_inactive',
            'products.not_for_selling',
            'products.product_custom_field1',
            'products.product_custom_field2',
            'products.product_custom_field3',
            'products.product_custom_field4',
            'products.alert_quantity',
            DB::raw('SUM(vld.qty_available) as current_stock'),
            DB::raw('MAX(v.sell_price_inc_tax) as max_price'),
            DB::raw('MIN(v.sell_price_inc_tax) as min_price'),
            DB::raw('MAX(v.dpp_inc_tax) as max_purchase_price'),
            DB::raw('MIN(v.dpp_inc_tax) as min_purchase_price')
        );
    
        // Apply filters from request
        $type = request()->get('type', null);
        if (!empty($type)) {
            $products->where('products.type', $type);
        }
    
        $category_id = request()->get('category_id', null);
        if (!empty($category_id)) {
            $products->where('products.category_id', $category_id);
        }
    
        $brand_id = request()->get('brand_id', null);
        if (!empty($brand_id)) {
            $products->where('products.brand_id', $brand_id);
        }
    
        $unit_id = request()->get('unit_id', null);
        if (!empty($unit_id)) {
            $products->where('products.unit_id', $unit_id);
        }
    
        $tax_id = request()->get('tax_id', null);
        if (!empty($tax_id)) {
            $products->where('products.tax', $tax_id);
        }
    
        $active_state = request()->get('active_state', null);
        if ($active_state == 'active') {
            $products->Active();
        }
        if ($active_state == 'inactive') {
            $products->Inactive();
        }
        
        $not_for_selling = request()->get('not_for_selling', null);
        if ($not_for_selling == 'true') {
            $products->ProductNotForSales();
        }
    
        // Apply pagination
        $limit = request()->get('limit', 15);
        $page = request()->get('page', 1);
        
        $results = $products->groupBy('products.id')
                          ->paginate($limit, ['*'], 'page', $page);
    
        // Format product data for JSON response
        $formattedProducts = $results->map(function ($row) {
            $data = [
                'id' => $row->id,
                'name' => $row->product,
                'image_url' => $row->image_url,
                'type' => $row->type,
                'category' => $row->category,
                'sub_category' => $row->sub_category,
                'sku' => $row->sku,
                'unit' => $row->unit,
                'brand' => $row->brand,
                'tax' => $row->tax,
                'store_name' => $row->store ? $row->store->name_ar : 'No Parent',
                'printer' => $row->printer ? $row->printer->name : '',
                'is_inactive' => (bool)$row->is_inactive,
                'not_for_selling' => (bool)$row->not_for_selling,
                'custom_fields' => [
                    'field1' => $row->product_custom_field1,
                    'field2' => $row->product_custom_field2,
                    'field3' => $row->product_custom_field3,
                    'field4' => $row->product_custom_field4,
                ]
            ];
            
            // Add stock information if enabled
            if ($row->enable_stock) {
                $data['stock'] = [
                    'current_stock' => (float)$row->current_stock,
                    'alert_quantity' => (float)$row->alert_quantity,
                    'unit' => $row->unit
                ];
            }
            
            // Add pricing information
            $data['pricing'] = [
                'min_price' => (float)$row->min_price,
                'max_price' => (float)$row->max_price,
                'min_purchase_price' => (float)$row->min_purchase_price,
                'max_purchase_price' => (float)$row->max_purchase_price
            ];
            
            // Add locations
            $data['locations'] = $row->product_locations->pluck('name');
            
            return $data;
        });
    
        // Create response with pagination metadata
        return response()->json([
            'data' => $formattedProducts,
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ]);
    }
}
