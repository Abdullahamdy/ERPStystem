<?php

namespace App\Http\Controllers\Store;

use App\BusinessLocation;
use App\Stores;
use App\Utils\ModuleUtil;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Manufacturing\Utils\ManufacturingUtil;

class HeadsStoresController extends Controller
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

            $tables = Stores::head()->where('business_id',$business_id)->select(
                'stores.store_number as store_number',
                'stores.name_ar as name_ar',
                'stores.name_en as name_en',
                'stores.quantity as quantity',
                'stores.type_cost as type_cost',
                'stores.id as id'
            );


            return Datatables::of($tables)
            ->addColumn('type_cost_text', function ($table) {
                return $table->type_cost_text;
            })
                ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'Store\HeadsStoresController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                   @endrole
                   @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'Store\HeadsStoresController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   @endrole'
                )
                ->removeColumn('id')
                ->removeColumn('type_cost')
                ->escapeColumns(['action'])
                ->make(true);
        }


        return view('store.head_store.table.index');
    }

    public function create()
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
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

        return view('store.head_store.table.create')
            ->with(compact('business_locations','store_type','cost_type','status'));
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
            return view('store.head_store.table.edit')
                ->with(compact('table','business_locations','store_type','cost_type','status'));
        }
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->all();
                $table = Stores::findOrFail($id);
                $table->update($input);

                $output = ['success' => true,
                            'msg' => __("lang_v1.updated_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
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

        return view('store.head_store.table.show');
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

                $output = ['success' => true,
                            'msg' => __("lang_v1.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
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
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'manufacturing_module'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $data  = $request->all();
            $data['store_number'] = random_int(1000000000, 9999999999);
            $data['type'] = 1;
            $data['business_id'] = $business_id;

           
            Stores::create($data);

            $output = [
                'success' => 1,
                'msg' => __("lang_v1.updated_success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
    }

    
}
