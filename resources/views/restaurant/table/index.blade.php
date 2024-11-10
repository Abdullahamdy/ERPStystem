@extends('layouts.app')
@section('title', __( 'restaurant.tables' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'restaurant.tables' )
        <small>@lang( 'restaurant.manage_your_tables' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">

	<div class="box">
        <div class="box-header">
        	<h3 class="box-title">@lang( 'restaurant.all_your_tables' )</h3>
            @can('restaurant.create')
            	<div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    	data-href="{{action('Restaurant\TableController@create')}}" 
                    	data-container=".tables_modal">
                    	<i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endcan
        </div>

        <div class="box-body">
            @can('restaurant.view')
           
            	<table class="table table-bordered table-striped" id="tables_table">
                    <div class="row mb-3" style="margin-right: 367px; margin-bottom: 5px;">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" style="margin-right: 14px;padding: 10px;background-color:#38393f;color:#fff" class="btn btn-default flower-filter" data-floor="1">الطابق الأول</button>
                                <button type="button" style="margin-right: 19px;padding: 10px;background-color:#38393f;color:#fff" class="btn btn-default flower-filter" data-floor="2">الطابق الثاني</button>
                                <button type="button" style="margin-right: 36px;padding: 10px;background-color:#38393f;color:#fff" class="btn btn-default flower-filter" data-floor="3">الطابق الثالث</button>
                            </div>
                        </div>
                    </div>
            		<thead>
            			<tr>
            				<th>@lang( 'restaurant.table' )</th>
                            <th>@lang( 'purchase.business_location' )</th>
                            <th>@lang( 'purchase.user' )</th>
                            <th>@lang( 'purchase.flowersNumber' )</th>
            				<th>@lang( 'restaurant.description' )</th>
            				<th>@lang( 'messages.action' )</th>
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
    // Add filter functionality
    var currentFloor = '';
    $('.flower-filter').on('click', function() {
        $('.flower-filter').removeClass('btn-primary').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-primary');
        currentFloor = $(this).data('floor');
        tables_table.ajax.reload();
    });

    // Your existing modal form submit handler
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

    // DataTables initialization
    var tables_table = $('#tables_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/modules/tables',
            data: function(d) {
                d.flower_number = currentFloor;
            }
        },
        columnDefs: [ {
            "targets": 3,
            "orderable": false,
            "searchable": false
        } ],
        columns: [
            { data: 'name', name: 'res_tables.name'  },
            { data: 'location', name: 'BL.name'},
            { data: 'user_id', name: 'user_id'},
            { data: 'flower_number', name: 'flower_number'},
            { data: 'description', name: 'description'},
            { data: 'action', name: 'action'}
        ],
    });

    // Edit button handler
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

    // Delete button handler
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