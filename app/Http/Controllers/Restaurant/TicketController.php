<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Ticket;
use App\Utils\BusinessUtil;
use Datatables;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public $businessUtil;
     public function __construct(
       
        BusinessUtil $businessUtil
     
    ) {
        $this->businessUtil = $businessUtil;
   
    }
    public function index()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
    
            $tables = Ticket::where('status', true)
                ->where('business_id', $business_id)
                ->select(
                    'tickets.product_count',
                    'tickets.ticket_number',
                    'tickets.product_id',
                    'tickets.number_of_day', // عدد الساعات
                    'tickets.status',
                    'tickets.id as id',
                    'tickets.price',
                    'tickets.created_at as created_at'
                );
    
            return Datatables::of($tables)
                ->addColumn('action', '
                    @role("Admin#' . $business_id . '")
                    <button data-href="{{action(\'Restaurant\TicketController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_table_button">
                        <i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")
                    </button>&nbsp;
                    @endrole
                    @role("Admin#' . $business_id . '")
                    <button data-href="{{action(\'Restaurant\TicketController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_table_button">
                        <i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")
                    </button>
                    @endrole
                ')
                ->editColumn('status', function ($row) {
                    return $row->status
                        ? ' <small class="label bg-green no-print">' . __("ticket.active") . '</small>'
                        : ' <small class="label bg-red no-print">' . __("ticket.in_active") . '</small>';
                })
                ->addColumn('is_disabled', function ($row) {
                    // حساب وقت انتهاء التذكرة
                    $createdAt = $row->created_at;
                    $expiryDate = $createdAt->addHours($row->number_of_day);
    
                    // التحقق إذا انتهت صلاحية التذكرة
                    return now() > $expiryDate;
                })
                ->addColumn('day_off', function ($row) {
                    // حساب وقت انتهاء التذكرة
                    $createdAt = $row->created_at;
                    $expiryDate = $createdAt->addHours($row->number_of_day);
    
                    // عرض رسالة انتهاء الصلاحية إذا انتهت
                    $text = now() > $expiryDate
                        ? ' <small class="label bg-red no-print">  منتهية  </small>'
                        : '';
    
                    // عرض التاريخ والوقت
                    return $expiryDate->format("d/m/Y H:i") . $text;
                })
                ->filter(function ($query) {
                    // فلترة التذاكر بناءً على الصلاحية (إذا لم تنتهي)
                    $query->whereRaw('created_at + interval number_of_day hour > now()');
                })
                ->removeColumn('id')
                ->escapeColumns(['action'])
                ->make(true);
        }
    
        return view('ticket.table.index');
    }
    

    public function updateStatus(Request $request){
        if($request->id){
          $ticket =  Ticket::find($request->id);
          $ticket->update(['status'=>false]);
          return response()->json(['status'=>true,'msg'=>'تم التأكيد بنجاح'],200);
        }
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
            $data = $request->all();
            $data['ticket_number'] = random_int(1000000000, 9999999999);
            $data['business_id'] = $business_id;
    
            // Create the ticket
            $ticket = Ticket::create($data);
    
            // Generate ticket content for printing
            $request->input('print_ticket', false);
            
                $ticket_content = $this->ticketContent(
                    $business_id,
                    $ticket->id,
                    null,
                    false
                );
    
                if ($ticket_content['is_enabled'] && $ticket_content['print_type'] == 'browser') {
                    return response()->json([
                        'success' => true,
                        'html_content' => $ticket_content['html_content']
                    ]);
                }
            
    
            return response()->json([
                'success' => true,
                'message' => __("Ticket created successfully")
            ]);
        } catch (\Exception $e) {
            dd($e);
            \Log::error("Error creating ticket: {$e->getMessage()}");
    
            return response()->json([
                'success' => false,
                'message' => __("Something went wrong")
            ]);
        }
    }


    private function ticketContent(
        $business_id,
        $ticket_id,
        $printer_type = null,
        $is_package_slip = false,
        $invoice_layout_id = null
    ) {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];
    
        $business_details = $this->businessUtil->getDetails($business_id);
        $ticket = Ticket::find($ticket_id);
    
        if (!$ticket) {
            return $output; // Return default output if ticket not found
        }
    
        // Enable printing
        $output['is_enabled'] = true;
    
        $invoice_layout = $this->businessUtil->invoiceLayout($business_id, $invoice_layout_id);
    
        $ticket_details = [
            'ticket_number' => $ticket->ticket_number,
            'product_count' => $ticket->product_count,
            'number_of_day' => $ticket->number_of_day,
            'status' => $ticket->status,
            'price' => $ticket->price,
            'business_details' => $business_details
        ];
    
        $output['data'] = $ticket_details;
    
        if ($printer_type == 'printer') {
            $output['print_type'] = 'printer';
            $output['printer_config'] = $this->businessUtil->printerConfig($business_id);
        } else {
            $output['html_content'] = view('ticket.table.print', compact('ticket_details'))->render();
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
        $ticket = Ticket::find($id);
        if(!$ticket){

            return response()->json(['status'=>false,'msg'=>'ticket not found or not selected ticket']);
        }
        return response()->json(['success'=>true,'product_count'=>$ticket->product_count]);

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
