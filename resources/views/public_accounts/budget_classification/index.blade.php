@extends('layouts.app')
@section('title', __('budgetclassification.budget_classification'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('budgetclassification.budget_classification')
            <small>@lang('budgetclassification.budget_classification_manage')</small>
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
                <h3 class="box-title">@lang('budgetclassification.budget_classification')</h3>
                @can('restaurant.create')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal"
                            data-href="{{ action('\App\Http\Controllers\BudgetClassificationController@create') }}"
                            data-container=".tables_modal">
                            <i class="fa fa-plus"></i> @lang('messages.add')</button>
                    </div>
                @endcan
            </div>
            <div class="box-body">
                @can('restaurant.view')
                    <table class="table table-bordered table-striped" id="budget_classifications_table">
                        <thead>
                            <tr>
                                <th>@lang('budgetclassification.name')</th>
                                <th>@lang('budgetclassification.type')</th>
                                <th>@lang('budgetclassification.transaction_side')</th>
                                <th>@lang('budgetclassification.code')</th>
                                <th>@lang('budgetclassification.net_profit_loss')</th>
                                <th>@lang('budgetclassification.action')</th>
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
            $(document).on('submit', 'form#table_add_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
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
            var tables_table = $('#budget_classifications_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/account/budget-classification',
                columnDefs: [{
                    "targets": 3,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                   
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'transaction_side',
                        name: 'transaction_side'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'net_profit_loss_display',
                        name: 'net_profit_loss_display'
                    },
                    {
                        data: 'action',
                        name: 'action',

                        orderable: false,
                        searchable: false
                    }
                ],
            });

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
                                if (result.success == true) {
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

            $(document).on('click', 'button.delete_table_button', function() {
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
                            success: function(result) {
                                if (result.success == true) {
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
