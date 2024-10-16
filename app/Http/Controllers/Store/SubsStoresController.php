<?php

namespace App\Http\Controllers\Store;

use App\Business;
use App\BusinessLocation;
use App\Stores;
use App\Utils\ModuleUtil;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Manufacturing\Utils\ManufacturingUtil;

class SubsStoresController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $mfgUtil;
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil, ManufacturingUtil $mfgUtil)
    {
        $this->moduleUtil = $moduleUtil;
        $this->mfgUtil = $mfgUtil;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $tables = Stores::sub()
                ->select(
                    'stores.store_number as store_number',
                    'stores.name_ar as name_ar',
                    'stores.name_en as name_en',
                    'stores.quantity as quantity',
                    'stores.id as id',
                    'stores.type_cost as type_cost',
                    'stores.parent_id' 
                );


            return Datatables::of($tables)
                ->addColumn('type_cost_text', function ($table) {
                    return $table->type_cost_text;
                })
                ->addColumn('parent_name', function ($table) {
                    return $table->parent ? $table->parent->name_ar : 'No Parent';
                })
                ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'Store\SubsStoresController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                   @endrole
                   @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'Store\SubsStoresController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   @endrole'
                )
                ->removeColumn('id')
                ->removeColumn('type_cost')
                ->escapeColumns(['action'])
                ->make(true);
        }


        return view('store.sub_store.table.index');
    }

    public function create()
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business_id = request()->session()->get('user.business_id');
        $mainStores = Stores::forDropdown($business_id, false);
        $store_type = [
            1 => 'مخزني',
            2 => 'خدمي',
            3 => 'مصنع',
        ];
        $cost_type = [
            1 => 'الوارد اولا يخرج اولا',
            2 => 'الوارد اخيرا يخرج اولا',
            3 => 'متوسط التكلفة',
        ];
        $status = [
            0 => ' غير نشط',
            1 => 'نشط',
        ];
        return view('store.sub_store.table.create')
            ->with(compact('business_locations', 'mainStores', 'status', 'cost_type', 'store_type'));
    }



    public function edit($id)
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $table = Stores::find($id);
            $business_locations = BusinessLocation::forDropdown($business_id);
            $mainStores = Stores::forDropdown($business_id, false);
            $store_type = [
                1 => 'مخزني',
                2 => 'خدمي',
                3 => 'مصنع',
            ];

            $cost_type = [
                1 => 'الوارد اولا يخرج اولا',
                2 => 'الوارد اخيرا يخرج اولا',
                3 => 'متوسط التكلفة',
            ];
            $status = [
                0 => ' غير نشط',
                1 => 'نشط',
            ];
            return view('store.sub_store.table.edit')
                ->with(compact('table', 'business_locations', 'store_type', 'cost_type', 'mainStores', 'status'));
        }
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $table = Stores::findOrFail($id);
                $parent =  Stores::find($table->parent_id);

                $input = $request->except(['type_cost_hidden', 'store_type_hidden']);
                $input['type'] = 0;
                $input['branch_id'] = $parent->branch_id;
                $input['type_cost'] = $request->input('type_cost_hidden');
                $input['store_type'] = $request->input('store_type_hidden');

                $table->update($input);

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.updated_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
    public function show()
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        return view('store.sub_store.table.show');
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $table = Stores::findOrFail($id);
                $table->delete();

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.deleted_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {

                $parent =  Stores::find($request->parent_id);
                
                $input = $request->except(['type_cost_hidden', 'store_type_hidden']);

                $input['type'] = 0;
                $input['branch_id'] = $parent->branch_id;
                $input['store_number'] = random_int(1000000000, 9999999999);
                $input['type_cost'] = $request->input('type_cost_hidden');
                $input['store_type'] = $request->input('store_type_hidden');

                $table = Stores::create($input);

                $output = [
                    'success' => true,
                    'data' => $table,
                    'msg' => __("lang_v1.added_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
    public function getParentDetails($id)
    {
        $store = Stores::findOrFail($id);
        return response()->json([
            'type_cost' => $store->type_cost,
            'store_type' => $store->store_type
        ]);
    }
}
