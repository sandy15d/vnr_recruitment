@extends('layouts.master')
@section('title', 'Designation Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Designation</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncDesignation"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="designationtable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                        <tr>
                            <td class="td-sm">S.No.</td>
                            <td>Designation Name</td>
                            <td>Designation Code</td>
                            <td>Department</td>
                            {{--  <td>Company</td> --}}
                            <td>MW Category</td>
                            {{--  <td>Status</td> --}}
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
        $('#designationtable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllDesignation') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                'className' : 'text-center'
            },
                {
                    data: 'designation_name',
                    name: 'designation_name'
                },
                {
                    data: 'designation_code',
                    name: 'designation_code'
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                /* {
                    data: 'CompanyCode',
                    name: 'CompanyCode',
                    'className' : 'text-center'
                }, */
                {
                    data:'Category',
                    name:'Category',
                    'className':'text-center'
                },
                /* {
                    data: 'DesigStatus',
                    name: 'DesigStatus',
                    'className' : 'text-center'
                } */
            ],

        });

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncDesignation', function() {
            var url = '<?= route('syncDesignation') ?>';
            $('#syncDesignation').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Designation Data from ESS',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Synchronize',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false,

            }).then(function(result) {
                if (result.value) {
                    $.post(url, function(data) {
                        if (data.status == 200) {
                            $('#syncDesignation').html('Sync');
                            $('#designationtable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            $('#syncDesignation').html('Sync');
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
