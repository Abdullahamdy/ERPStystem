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
        $category_id = $request->get('category_id');

        $brand_id = $request->get('brand_id');
        $location_id = "13";
        $term = $request->get('term');

        $business_id = $request->session()->get('user.business_id');
        $business = $request->session()->get('business');

        $products = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
            ->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
            ->leftjoin(
                'variation_location_details AS VLD',
                function ($join) use ($location_id) {
                    $join->on('variations.id', '=', 'VLD.variation_id');
                }
            )
            ->where('p.business_id', $business_id)
            ->where('p.type', '!=', 'modifier')
            ->where('p.is_inactive', 0)
            ->where('p.not_for_selling', 0)
            //Hide products not available in the selected location
            ->where(function ($q) use ($location_id) {
                $q->where('pl.location_id', $location_id);
            });

        $products = $products->select(
            'p.id as product_id',
            'p.name as product_name',
            
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
                'msg' => __('messages.group_created_successfully'),
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
        $business_id = $request->session()->get('user.business_id');
        $location_id = "13";
        $productGroup = ProductGroup::findOrFail($id);
        $products = Variation::join('products as p', 'variations.product_id', '=', 'p.id')
        ->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
        ->leftjoin(
            'variation_location_details AS VLD',
            function ($join) use ($location_id) {
                $join->on('variations.id', '=', 'VLD.variation_id');
            }
        )
        ->where('p.business_id', $business_id)
        ->where('p.type', '!=', 'modifier')
        ->where('p.is_inactive', 0)
        ->where('p.not_for_selling', 0)
        //Hide products not available in the selected location
        ->where(function ($q) use ($location_id) {
            $q->where('pl.location_id', $location_id);
        });

    $products = $products->select(
        'p.id as product_id',
        'p.name as product_name',
        
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
    
            return response()->json(['success' => true, 'msg' => __('messages.updated_successfully')]);
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
