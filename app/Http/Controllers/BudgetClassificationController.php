<?php

namespace App\Http\Controllers;

use App\BudgetClassification;
use Illuminate\Http\Request;
use Datatables;

class BudgetClassificationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
        $business_id = session()->get('user.business_id');
       
        $costcenters = BudgetClassification::where('business_id',$business_id)->select(
            'budget_classifications.id as id',
            'budget_classifications.code as code',
            'budget_classifications.name as name',
            'budget_classifications.transaction_side as transaction_side',
            'budget_classifications.net_profit_loss as net_profit_loss',
            'budget_classifications.type as type'
        );
          
            return DataTables::of($costcenters)
            ->addColumn(
                    'action',
                    '@role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'BudgetClassificationController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                       &nbsp;
                   @endrole
                   @role("Admin#' . $business_id . '")
                       <button data-href="{{action(\'BudgetClassificationController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   @endrole'
                )
                ->addColumn(
                    'net_profit_loss_display',
                    function ($row) {
                        return $row->net_profit_loss 
                            ? '<span class="text-success">✔</span>' 
                            : '<span class="text-danger">✘</span>';
                    }
                )
                ->removeColumn('id')
                ->escapeColumns(['action'])
                ->make(true);
                          
            }
        return view('public_accounts.budget_classification.index');
            
    
}

    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        $public_accounts = BudgetClassification::where('business_id', $business_id)
                                     ->get();

        return view('public_accounts.budget_classification.create')
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
                'net_profit_loss' => 'sometimes|boolean',
            ]);
    
            $input['business_id'] = session()->get('user.business_id');
            $input['code'] = random_int(1000000000, 9999999999);;
    
            $costCenter = BudgetClassification::create($input);
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
        $budget_classification = BudgetClassification::where('id', $id)
                                ->firstOrFail();
                                return view('public_accounts.budget_classification.edit', compact('budget_classification'));
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
                'net_profit_loss' => 'sometimes|boolean',
            ]);
             !isset($input['net_profit_loss']) ? $input['net_profit_loss'] = 0 :  $input['net_profit_loss'] = 1;

    
            $business_id = session()->get('user.business_id');
            $budget_classification = BudgetClassification::where('business_id', $business_id)
                                                       ->findOrFail($id);
    
            $budget_classification->update($input);
    
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

                $table = BudgetClassification::findOrFail($id);
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
