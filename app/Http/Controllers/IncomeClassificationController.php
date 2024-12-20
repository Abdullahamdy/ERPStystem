<?php

namespace App\Http\Controllers;

use App\IncomeClassification;
use Illuminate\Http\Request;
use Datatables;
class IncomeClassificationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
        $business_id = session()->get('user.business_id');
       
        $costcenters = IncomeClassification::where('business_id',$business_id)->select(
            'income_classifications.id as id',
            'income_classifications.code as code',
            'income_classifications.name as name',
            'income_classifications.transaction_side as transaction_side',
            'income_classifications.type as type'
        );
          
            return DataTables::of($costcenters)
            ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'IncomeClassificationController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                   @endrole
                   @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'IncomeClassificationController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   @endrole'
                )
                ->removeColumn('id')
                ->removeColumn('type_cost')
                ->escapeColumns(['action'])
                ->make(true);
                          
            }
        return view('public_accounts.income_classification.index');
            
    
}

    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        $public_accounts = IncomeClassification::where('business_id', $business_id)
                                     ->get();

        return view('public_accounts.income_classification.create')
                ->with(compact('public_accounts'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
    
        try {
            $input = $request->validate([
                'name' => 'required|string|max:255',
                'transaction_side' => 'required|string|max:255',
                'type' => 'required|string',
            ]);
    
            $input['business_id'] = session()->get('user.business_id');
            $input['code'] = random_int(1000000000, 9999999999);;
    
            $costCenter = IncomeClassification::create($input);
            $output = [
                'success' => 1,
                'msg' => __("lang_v1.updated_success")
            ];
        } catch (\Exception $e) {
            dd($e);
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
        $income_classification = IncomeClassification::where('id', $id)
                                ->firstOrFail();
                                return view('public_accounts.income_classification.edit', compact('income_classification'));
                            }
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
    
        try {
            $input = $request->validate([
                'name' => 'required|string|max:255',
                'transaction_side' => 'required|string|max:255',
                'type' => 'required|string',
            ]);
    
            $business_id = session()->get('user.business_id');
            $income_classification = IncomeClassification::where('business_id', $business_id)
                                                       ->findOrFail($id);
    
            $income_classification->update($input);
    
            $output = [
                'success' => 1,
                'msg' => __('lang_v1.updated_success')
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = [
                'success' => 0,
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

                $table = IncomeClassification::findOrFail($id);
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
