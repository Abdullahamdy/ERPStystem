@extends('layouts.app')
@section('title', __('store.subs_stores'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('store.subs_stores')
            <small>@lang('store.manage_your_subs_stores')</small>
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
                <h3 class="box-title">@lang('store.subs_stores')</h3>
                @can('restaurant.create')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal"
                            data-href="{{ action('\App\Http\Controllers\Restaurant\ProductsGroupController@create') }}"
                            data-container=".tables_modal">
                            <i class="fa fa-plus"></i> @lang('messages.add')</button>
                    </div>
                @endcan
            </div>
            <div class="box-body">
                @can('restaurant.view')
                    <table class="table table-bordered table-striped" id="group_products_table">
                        <thead>
                            <tr>
                                <th>اسم المجموعة</th>
                                <th>المنتجات</th>
                                <th>@lang('store.action')</th>
                            </tr>
                        </thead>
                    </table>
                @endcan
            </div>
        </div>

        <div class="modal fade tables_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable for Product Groups
        var tables_table = $('#group_products_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/modules/products-group', // Ensure this endpoint returns the expected data
                type: 'GET'
            },
            columnDefs: [{
                targets: [1], // Index of the action column
                orderable: false,
                searchable: false
            }],
            columns: [
                {
                    data: 'group_name', // Product group name column
                    name: 'group_name',
                    render: function(data) {
                        return data || '-'; // Gracefully handle empty values
                    }
                },
                {
                    data: 'products', // This should match the backend 'products' column
                    name: 'products',
                    render: function(data) {
                        return data || '-'; // Handle null or empty data gracefully
                    }
                },
                {
                    data: 'action', // This should match the backend 'action' column
                    name: 'action',
                    render: function(data, type, row) {
                        return data || '-'; // Handle null or empty data gracefully
                    }
                }
            ]
        });

        // Handle Add Table Form Submission
        $(document).on('submit', 'form#table_add_form', function(e) {
            e.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success: function(result) {
                    if (result.success) {
                        $('div.tables_modal').modal('hide');
                        toastr.success(result.msg);
                        tables_table.ajax.reload(); // Reload DataTable
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });

        // Handle Edit Button Click
        $(document).on('click', 'button.edit_table_button', function() {
            $("div.tables_modal").load($(this).data('href'), function() {
                $(this).modal('show');

                $('form#table_edit_form').submit(function(e) {
                    e.preventDefault();
                    var data = $(this).serialize();

                    $.ajax({
                        method: "POST",
                        url: $(this).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function(result) {
                            if (result.success) {
                                $('div.tables_modal').modal('hide');
                                toastr.success(result.msg);
                                tables_table.ajax.reload(); // Reload DataTable
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            });
        });

        // Handle Delete Button Click
        $(document).on('click', 'button.delete_table_button', function() {
            var href = $(this).data('href');

            swal({
                title: LANG.sure,
                text: LANG.confirm_delete_table,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success) {
                                toastr.success(result.msg);
                                tables_table.ajax.reload(); // Reload DataTable
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
