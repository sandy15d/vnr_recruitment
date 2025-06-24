@extends('layouts.master')
@section('title', 'Region Zone Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Region & Zone</div>

            <div class="ms-auto">

                <button class="btn btn-sm btn--red" id="syncRegion"><i class="fadeIn animated bx bx-sync"></i>Sync
                </button>
            </div>
        </div>
        <!--end breadcrumb-->

        <hr/>
        <div class="row mt-2">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="title">Zone master</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed" id="zonetable" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                <tr>
                                    <td class="td-sm">S.No.</td>
                                    <td>Zone Name</td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="title">Region master</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed" id="regionTable" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                <tr>
                                    <td class="td-sm">S.No.</td>
                                    <td>Region Name</td>
                                    <td>Zone Name</td>
                                    <td>Status</td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_section')
    <script>


        $('#zonetable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllZone') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                'className': 'text-center'
            },
                {
                    data: 'ZoneName',
                    name: 'ZoneName'
                }
            ],

        });
        $('#regionTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllRegion') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                'className': 'text-center'
            },
                {
                    data: 'RegionName',
                    name: 'RegionName'
                },
                {
                    data: 'ZoneName',
                    name: 'ZoneName'
                },
                {
                    data: 'Status',
                    name: 'Status',
                }
            ],

        });


        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncRegion', function () {
            var url = '<?= route('syncRegion') ?>';
            $('#syncRegion').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Region and Zone Data from ESS',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Synchronize',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false,


            }).then(function (result) {
                if (result.value) {
                    $.post(url, function (data) {
                        if (data.status == 200) {
                            $('#loader').modal('hide');
                            $('#syncRegion').html('Sync');
                            $('#zonetable').DataTable().ajax.reload(null, false);
                            $('#regionTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);

                        } else {
                            toastr.error(data.msg);
                            $('#syncRegion').html('Sync');
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
