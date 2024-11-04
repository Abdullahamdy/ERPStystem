@extends('layouts.app')
@section('title', __( 'ticket.tickets' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'ticket.tickets' )
        <small>@lang( 'ticket.manage_your_tickets' )</small>
    </h1>
   
</section>

<!-- Main content -->
<section class="content">

	<div class="box">
        <div class="box-header">
        	<h3 class="box-title">@lang( 'ticket.tickets' )</h3>
            @can('restaurant.create')
            	<div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    	data-href="{{action('\App\Http\Controllers\Restaurant\TicketController@create')}}" 
                    	data-container=".tables_modal">
                    
                    	<i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endcan
        </div>
        <div class="box-body">
            @can('restaurant.view')
            	<table class="table table-bordered table-striped" id="tickets_table">
            		<thead>
            			<tr>
            				<th>@lang( 'ticket.ticket_number' )</th>
                            <th>@lang( 'ticket.product_count' )</th>
            				<th>@lang( 'ticket.products' )</th>
            				<th>@lang( 'ticket.number_of_day' )</th>
            				<th>@lang( 'ticket.status' )</th>
            				<th>@lang('ticket.day_off')</th>
            				<th>@lang( 'ticket.action' )</th>
                         
            			</tr>
            		</thead>
            	</table>
            @endcan
        </div>
    </div>

    <div class="modal fade tables_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('submit', 'form#table_add_form', function(e){
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result){
                        if(result.success == true){
                            $('div.tables_modal').modal('hide');
                            toastr.success(result.msg);
                            tables_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

            //Brands table
            var tables_table = $('#tickets_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/modules/tickets',
                    columnDefs: [ {
                        "targets": 3,
                        "orderable": false,
                        "searchable": false
                    } ],
                    columns: [
                        { data: 'ticket_number', name: 'tickets.ticket_number'  },
                        { data: 'product_count', name: 'tickets.product_count'},
                        { data: 'product_id', name: 'tickets.product_id'},
                        { data: 'number_of_day', name: 'tickets.number_of_day' },
                        { data: 'status', name: 'tickets.status'},
                        { data: 'day_off', name: 'day_off'},
                        { data: 'action', name: 'action'}
                    
                      
                    ],
             createdRow: function(row, data, dataIndex) {
                console.log(data.is_disabled)
                     if (data.is_disabled == true) {
                $(row).css('background-color', '#919176')
                .css('color', '#fffff');
            }
        }
                });

            $(document).on('click', 'button.edit_table_button', function(){

                $( "div.tables_modal" ).load( $(this).data('href'), function(){

                    $(this).modal('show');

                    $('form#table_edit_form').submit(function(e){
                        e.preventDefault();
                        var data = $(this).serialize();

                        $.ajax({
                            method: "POST",
                            url: $(this).attr("action"),
                            dataType: "json",
                            data: data,
                            success: function(result){
                                if(result.success == true){
                                    $('div.tables_modal').modal('hide');
                                    toastr.success(result.msg);
                                    tables_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    });
                });
            });

            $(document).on('click', 'button.delete_table_button', function(){
                swal({
                  title: LANG.sure,
                  text: LANG.confirm_delete_table,
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result){
                                if(result.success == true){
                                    toastr.success(result.msg);
                                    tables_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection