<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Datatables;

use App\Restaurant\ResTable;
use App\BusinessLocation;
use App\User;

class TableController extends Controller
{
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
        
        $tables = ResTable::where('res_tables.business_id', $business_id)
                    ->join('business_locations AS BL', 'res_tables.location_id', '=', 'BL.id')
                    ->select([
                        'res_tables.name as name', 
                        'BL.name as location',
                        'res_tables.description', 
                        'res_tables.id',
                        'res_tables.user_id as user_id',
                        'res_tables.flower_number as flower_number'
                    ]);

        // Add floor filter
        if (request()->get('flower_number')) {
            $tables->where('res_tables.flower_number', request()->get('flower_number'));
        }

        return Datatables::of($tables)
            ->addColumn(
                'action',
                '@role("Admin#' . $business_id . '")
                <button data-href="{{action(\'Restaurant\TableController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                    &nbsp;
                @endrole
                @role("Admin#' . $business_id . '")
                    <button data-href="{{action(\'Restaurant\TableController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endrole'
            )
            ->editColumn('user_id', function ($row) {
                return $row->user ? $row->user->first_name . $row->user->surname  : '' ;
            })
            ->editColumn('flower_number', function ($row) {
                $floors = [
                    1 => 'الطابق الأول',
                    2 => 'الطابق الثاني',
                    3 => 'الطابق الثالث',
                ];
                
                return $floors[$row->flower_number] ?? '';
            })
            ->removeColumn('id')
            ->escapeColumns(['action'])
            ->make(true);
    }


        return view('restaurant.table.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }
        $followres = [
            1 => 'الطابق الأول',
            2 => 'الطابق الثاني',
            3 => 'الطابق الثالث',
        ];
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);
        $business_id = request()->session()->get('user.business_id');
        $users = User::forDropdown($business_id, false);

        return view('restaurant.table.create')
            ->with(compact('business_locations','users','followres'));
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
        try {
            $input = $request->only(['name', 'description', 'location_id','user_id','flower_number']);
            $business_id = $request->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            $input['created_by'] = $request->session()->get('user.id');

            $table = ResTable::create($input);
            $output = ['success' => true,
                            'data' => $table,
                            'msg' => __("lang_v1.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
         
            $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        return view('restaurant.table.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $users = User::forDropdown($business_id, false);
            $followres = [
                1 => 'الطابق الأول',
                2 => 'الطابق الثاني',
                3 => 'الطابق الثالث',
            ];
            $table = ResTable::where('business_id', $business_id)->find($id);

            return view('restaurant.table.edit')
                ->with(compact('table','users','followres'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'description','user_id','flower_number']);
                $business_id = $request->session()->get('user.business_id');

                $table = ResTable::where('business_id', $business_id)->findOrFail($id);
                $table->name = $input['name'];

                $table->description = $input['description'];
                $table->user_id = $input['user_id'];
                $table->flower_number = $input['flower_number'];
                $table->save();

                $output = ['success' => true,
                            'msg' => __("lang_v1.updated_success")
                            ];
            } catch (\Exception $e) {
                dd($e);
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $table = ResTable::where('business_id', $business_id)->findOrFail($id);
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
