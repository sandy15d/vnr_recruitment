@extends('layouts.master')
@section('title', 'Vertical Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Department Vertical</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncVertical"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card  col-lg-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="verticaltable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>Company</td>
                                <td>Department</td>
                                <td>Vertical Name</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#verticaltable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllVertical') }}",
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'

                }
            },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible'
                    }
                },
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },
                {
                    data: 'company_code',
                    name: 'company_code',
                    'className': 'text-center'
                },
                {
                    data: 'DepartmentCode',
                    name: 'DepartmentCode'
                },
                {
                    data: 'VerticalName',
                    name: 'VerticalName'
                }
            ],

        });

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncVertical', function() {
            var url = '<?= route('syncVertical') ?>';
            $('#syncVertical').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Department Vertical Data from ESS',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Synchronize',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },

            }).then(function(result) {
                if (result.value) {
                    $.post(url, function(data) {
                        if (data.status==200) {
                            $('#syncVertical').html('Sync');
                            $('#verticaltable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                            $('#syncVertical').html('Sync');
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
