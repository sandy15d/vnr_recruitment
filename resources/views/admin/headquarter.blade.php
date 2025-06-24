@extends('layouts.master')
@section('title', 'Headquarter Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Headquarter</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncHeadquarter"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed" id="headquartertable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>Headquarter</td>
                                <td>State</td>
                                <td>Company</td>
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
        $('#headquartertable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllHeadquarter') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className' : 'text-center'
                },
                {
                    data: 'HqName',
                    name: 'HqName'
                },
                {
                    data: 'StateName',
                    name: 'StateName'
                },
                {
                    data: 'company_code',
                    name: 'company_code',
                    'className' : 'text-center'
                }
            ],

        });

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncHeadquarter', function() {
            var url = '<?= route('syncHeadquarter') ?>';
            $('#syncHeadquarter').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Headquaeter Data from ESS',
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

                        if (data.status==200) {
                            $('#headquartertable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                            $('#syncHeadquarter').html('Sync');
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
