<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\ProductGroup;
use App\SellingPriceGroup;
use App\Utils\BusinessUtil;
use App\Variation;
use Datatables;
use Illuminate\Http\Request;

class ProductsGroupController extends Controller
{
    protected $businessUtil;
    public function __construct(

        BusinessUtil $businessUtil

    ) {

        $this->businessUtil = $businessUtil;
    }
    public function index()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            // Query to get Product Groups with Products
            $tables = ProductGroup::with(['products' => function ($query) {
                $query->select('products.id', 'products.name'); // Fetch only necessary columns
            }])
                // Restrict to current business
                ->select('product_groups.id', 'product_groups.name'); // Select group ID and name

            return Datatables::of($tables)
                ->addColumn('group_name', function ($row) {
                    return $row->name; // Fetch the product group name
                })
                ->addColumn('products', function ($row) {
                    return $row->products->pluck('name')->implode(', '); // List product names
                })
                ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'Restaurant\ProductsGroupController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                       @endrole
                       @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'Restaurant\ProductsGroupController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                       @endrole'
                )
                ->removeColumn('id')
                ->escapeColumns(['action'])
                ->make(true);
        }

        return view('products-group.index');
    }



    public function create(Request $request)
    {

      if ($request->ajax()) {
        $category_id = $request->get('category_id');

        $brand_id = $request->get('brand_id');
        $term = $request->get('term');

        $business_id = $request->session()->get('user.business_id');
        $business = $request->session()->get('business');
        $location_id = !empty(request()->input('location_id')) ? request()->input('location_id') : null;
        $products = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
        ->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
        ->leftjoin(
            'variation_location_details AS VLD',
            function ($join) use ($location_id) {
                $join->on('variations.id', '=', 'VLD.variation_id');

                //Include Location
                if (!empty($location_id)) {
                    $join->where(function ($query) use ($location_id) {
                        //Check null to show products even if no quantity is available in a location.
                        //TODO: Maybe add a settings to show product not available at a location or not.
                        $query->orWhereNull('VLD.location_id');
                    });
                    ;
                }
            }
        )
                ->where('p.business_id', $business_id)
                ->where('p.type', '!=', 'modifier')
                ->where('p.is_inactive', 0)
                ->where('p.not_for_selling', 0)
                //Hide products not available in the selected location
                ->where(function ($q) use ($location_id) {
                });

    

    //Include check for quantity
   
    
    if (!empty($category_id) && ($category_id != 'all')) {
        $products->where(function ($query) use ($category_id) {
            $query->where('p.category_id', $category_id);
            $query->orWhere('p.sub_category_id', $category_id);
        });
    }
    if (!empty($brand_id) && ($brand_id != 'all')) {
        $products->where('p.brand_id', $brand_id);
    }

    if (!empty($request->get('is_enabled_stock'))) {
        $is_enabled_stock = 0;
        if ($request->get('is_enabled_stock') == 'product') {
            $is_enabled_stock = 1;
        }

        $products->where('p.enable_stock', $is_enabled_stock);
    }

    if (!empty($request->get('repair_model_id'))) {
        $products->where('p.repair_model_id', $request->get('repair_model_id'));
    }

    $products = $products->select(
        'p.id as product_id',
        'p.name as product_name',
        'p.type',
        'p.enable_stock',
        'p.image as product_image',
        'variations.id',
        'variations.name as variation',
        'VLD.qty_available',
        'variations.default_sell_price as selling_price',
        'variations.sub_sku'
    )
            ->orderBy('p.name', 'asc')
            ->pluck('product_name', 'product_id')->toArray();

        $price_groups = SellingPriceGroup::where('business_id', $business_id)->active()->pluck('name', 'id');

        $allowed_group_prices = [];
        foreach ($price_groups as $key => $value) {
            if (auth()->user()->can('selling_price_group.' . $key)) {
                $allowed_group_prices[$key] = $value;
            }
        }
    }


        return view('products-group.create', compact('products'));
    }

    public function store(Request $request)
    {

        try {
            $validated =  $request->all();

            \DB::beginTransaction();

            $group = ProductGroup::create([
                'name' => $validated['name'],
                'description' => $validated['description']
            ]);

            \Log::info('Syncing product IDs:', $validated['products']);
            $group->products()->sync($validated['products']); // Sync valid products

            \DB::commit();

            return response()->json([
                'success' => true,
                'msg' => __("lang_v1.added_success"),
            ]);
        } catch (\Exception $e) {
            dd($e);

            \Log::error("Error creating product group: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ], 500);
        }
    }

    public function edit($id,Request $request)
    {
        $productGroup = ProductGroup::findOrFail($id);

        
        $business_id = $request->session()->get('user.business_id');
        $business = $request->session()->get('business');
        $location_id = !empty(request()->input('location_id')) ? request()->input('location_id') : null;
        $products = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
        ->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
        ->leftjoin(
            'variation_location_details AS VLD',
            function ($join) use ($location_id) {
                $join->on('variations.id', '=', 'VLD.variation_id');

                //Include Location
                if (!empty($location_id)) {
                    $join->where(function ($query) use ($location_id) {
                        //Check null to show products even if no quantity is available in a location.
                        //TODO: Maybe add a settings to show product not available at a location or not.
                        $query->orWhereNull('VLD.location_id');
                    });
                    ;
                }
            }
        )
                ->where('p.business_id', $business_id)
                ->where('p.type', '!=', 'modifier')
                ->where('p.is_inactive', 0)
                ->where('p.not_for_selling', 0)
                //Hide products not available in the selected location
                ->where(function ($q) use ($location_id) {
                });

    

    //Include check for quantity
   
    
    if (!empty($category_id) && ($category_id != 'all')) {
        $products->where(function ($query) use ($category_id) {
            $query->where('p.category_id', $category_id);
            $query->orWhere('p.sub_category_id', $category_id);
        });
    }
    if (!empty($brand_id) && ($brand_id != 'all')) {
        $products->where('p.brand_id', $brand_id);
    }

    if (!empty($request->get('is_enabled_stock'))) {
        $is_enabled_stock = 0;
        if ($request->get('is_enabled_stock') == 'product') {
            $is_enabled_stock = 1;
        }

        $products->where('p.enable_stock', $is_enabled_stock);
    }

    if (!empty($request->get('repair_model_id'))) {
        $products->where('p.repair_model_id', $request->get('repair_model_id'));
    }

    $products = $products->select(
        'p.id as product_id',
        'p.name as product_name',
        'p.type',
        'p.enable_stock',
        'p.image as product_image',
        'variations.id',
        'variations.name as variation',
        'VLD.qty_available',
        'variations.default_sell_price as selling_price',
        'variations.sub_sku'
    )
        ->orderBy('p.name', 'asc')
        ->pluck('product_name', 'product_id')->toArray();
        $selectedProducts = $productGroup->products()->pluck('id')->toArray();

        return view('products-group.edit', compact('productGroup', 'products', 'selectedProducts'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);
    
        \DB::beginTransaction();
    
        try {
            $productGroup = ProductGroup::findOrFail($id);
    
            // Update the group details
            $productGroup->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
            ]);
    
            // Sync the selected products
            $productGroup->products()->sync($validatedData['products']);
    
            \DB::commit();
    
            return response()->json(['success' => true, 'msg' => __("lang_v1.updated_success")]);
        } catch (\Exception $e) {
            \DB::rollBack();
    
            return response()->json(['success' => false, 'msg' => __('messages.something_went_wrong')]);
        }
    }
    

    public function destroy($id)
    {
        try {
            \DB::beginTransaction();
    
            $productGroup = ProductGroup::findOrFail($id);
    
            $productGroup->products()->detach();
    
            $productGroup->delete();
    
            \DB::commit();
    
            $output = [
                'success' => true,
                'msg' => __("lang_v1.deleted_success")
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
    
            \Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());
    
            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
    
        return $output;
    }
    
}
