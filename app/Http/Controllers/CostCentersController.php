<?php

namespace App\Http\Controllers;

use App\CostCenter;
use Illuminate\Http\Request;
use Datatables;

class CostCentersController extends Controller
{
    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */

    /**
     * Display a listing of the resource.
     
     */
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
        $business_id = session()->get('user.business_id');
       
        $costcenters = CostCenter::select(
            'cost_centers.id as id',
            'cost_centers.name_ar as name_ar',
            'cost_centers.name_en as name_en',
            'cost_centers.type as type',
            'cost_centers.status as status',
            'cost_centers.center_code as center_code'
        );
          
            return DataTables::of($costcenters)
            ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'CostCentersController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                   @endrole
                   @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'CostCentersController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   @endrole'
                )
                ->removeColumn('id')
                ->removeColumn('type_cost')
                ->escapeColumns(['action'])
                ->make(true);
                          
            }
        return view('public_accounts.cost_centers.index');
            
    
}

    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        $public_accounts = CostCenter::where('business_id', $business_id)
                                     ->get();

        return view('public_accounts.cost_centers.create')
                ->with(compact('public_accounts'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
    
        try {
            $input = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'type' => 'required|string',
                'status' => 'required',
                'center_code' => 'required|string|unique:cost_centers,center_code'
            ]);
    
            $input['business_id'] = session()->get('user.business_id');
    
            $costCenter = CostCenter::create($input);
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
    
    public function edit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
        $business_id = session()->get('user.business_id');
        $cost_center = CostCenter::where('id', $id)
                                ->firstOrFail();
                                return view('public_accounts.cost_centers.edit', compact('cost_center'));
                            }
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
    
        try {
            $input = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'type' => 'required|string',
                'status' => 'required',
                'center_code' => 'required|string|unique:cost_centers,center_code,' . $id
            ]);
    
            $business_id = session()->get('user.business_id');
            $cost_center = CostCenter::where('business_id', $business_id)
                                    ->where('id', $id)
                                    ->firstOrFail();
    
            $cost_center->update($input);
    
            $output = ['success' => true,
                      'msg' => __('lang_v1.updated_success')
                     ];
        } catch (\Exception $e) {
            dd($e);
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                      'msg' => __('messages.something_went_wrong')
                     ];
        }
    
        return $output;
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $table = CostCenter::findOrFail($id);
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
}
