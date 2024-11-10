<script type="text/javascript">
    $('#task_modal').on('shown.bs.modal', function() {
        $('form#task_form .datepicker').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });

        $('form#task_form .select2').select2({
            dropdownParent: $(this)
        });

        tinymce.init({
            selector: 'textarea#to_do_description',
        });

        if ($('#media_upload').length) {
            $(this).find("div#media_upload").dropzone({
                url: $('#media_upload_url').val(),
                paramName: 'file',
                uploadMultiple: true,
                autoProcessQueue: false,
                addRemoveLinks: true,
                params: {
                    'model_id': $('#model_id').val(),
                    'model_type': $('#model_type').val(),
                    'model_media_type': $('#model_media_type').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    if (response.success) {
                        toastr.success(response.msg);
                        $("#task_modal").modal('hide');
                    } else {
                        toastr.error(response.msg);
                    }
                },
            });
        }

        //form validation
        $("form#task_form").validate();
    });

    //handle model ticket

    $(document).ready(function() {

        $('#exampleModal').on('shown.bs.modal', function() {
            $('#recipient').select2({
                dropdownParent: $('#exampleModal') // Attach dropdown to modal
            });
        });

        $('#exampleModal').on('hidden.bs.modal', function() {
            $('#recipient').select2('destroy');
        });
    });

    function handleRecipientChange(selectedValue) {
        if (selectedValue != "") {
            $.ajax({
                method: 'GET',
                url: "{{ url('modules/tickets/:id') }}".replace(':id', selectedValue),
                data: selectedValue,
                dataType: "json",
                success: function(result) {
                    if (result.success == true) {
                        localStorage.setItem('product_count', result.product_count);
                        toastr.info(` تحتوي  هذة التذكرة علي ${result.product_count} منتجات`);

                    } else {
                        toastr.error(result.msg);
                    }
                }
            });

        }
    }

  


    $('#task_modal').on('hide.bs.modal', function() {
        tinymce.remove("textarea#to_do_description");
    });

    //form submit
    $(document).on('submit', 'form#task_form', function(e) {
        e.preventDefault();
        var url = $(this).attr("action");
        var method = $(this).attr("method");
        var data = $("form#task_form").serialize();
        var ladda = Ladda.create(document.querySelector('.ladda-button'));
        ladda.start();
        $.ajax({
            method: method,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                ladda.stop();
                if (result.success == true) {
                    var myDropzone = Dropzone.forElement("#media_upload");

                    if (typeof result.todo_id !== 'undefined') {
                        myDropzone.options.params.model_id = result.todo_id;
                    }

                    myDropzone.processQueue();

                    if (typeof task_table !== 'undefined') {
                        task_table.ajax.reload();
                    }

                    if ($('#calendar').length) {
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    });
</script>
