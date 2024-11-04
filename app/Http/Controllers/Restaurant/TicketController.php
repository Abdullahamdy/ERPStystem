<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticket;
use Datatables;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $tables = Ticket::select(
                'tickets.product_count',
                'tickets.ticket_number',
                'tickets.product_id',
                'tickets.number_of_day',
                'tickets.status',
                'tickets.id as id'
            );


            return Datatables::of($tables) ->addColumn(
                'action',
                '@role("Admin#' . $business_id . '")
               <button data-href="{{action(\'Restaurant\TicketController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                   &nbsp;
               @endrole
               @role("Admin#' . $business_id . '")
                   <button data-href="{{action(\'Restaurant\TicketController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
               @endrole'
            )
            ->editColumn('status', function ($row) {
                if ($row->status == false) {
                    return  ' <small class="label bg-red no-print">' . __("ticket.in_active") . '</small>';
                } else {
                    return  ' <small class="label bg-green no-print">' . __("ticket.active") . '</small>';
                }
            })
            ->removeColumn('id')
            ->removeColumn('type_cost')
            ->escapeColumns(['action'])
            ->make(true);
        }


        return view('ticket.table.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $status = [
            0 => ' غير نشط',
            1 => 'نشط',
        ];

        return view('ticket.table.create')
            ->with(compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
       
        try {
            $data  = $request->all();
            $data['ticket_number'] = random_int(1000000000, 9999999999);
            $data['product_count'] = $data['product_count'];
            $data['number_of_day'] = $data['number_of_day'];
            $data['status'] = $data['status'];

           
            Ticket::create($data);

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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
     public function edit($id)
     {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $table = Ticket::find($id);
            $status = [
                0 => ' غير نشط',
                1 => 'نشط',
            ];
            return view('ticket.table.edit')
                ->with(compact('table','status'));
        } 
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $input = $request->all();
                $table = Ticket::findOrFail($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('access_tables')) {
             abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $table = Ticket::findOrFail($id);
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
