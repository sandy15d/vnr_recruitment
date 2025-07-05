@extends('layouts.master')
@section('title', 'Minimum Wage Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Minimum Wage</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncMW"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card  col-lg-6">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed text-center table-bordered" id="MWTable" style="width: 100%">
                        <thead class="bg-success text-light ">
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Sno</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="2" style="vertical-align: middle;">April</th>
                            <th colspan="2" style="vertical-align: middle;">October</th>

                        </tr>
                        <tr>
                            <th>Per Day</th>
                            <th>Per Month</th>
                            <th>Per Day</th>
                            <th>Per Month</th>
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
        $('#MWTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllMW') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data:'Category',
                    name:'Category'
                },
                {
                    data:'PerDayApr',
                    name:'PerDayApr'
                },
                {
                    data:'PerMonthApr',
                    name:'PerMonthApr'
                },
                {
                    data:'PerDayOct',
                    name:'PerDayOct'
                },
                {
                    data:'PerMonthOct',
                    name:'PerMonthOct'
                },

            ],

        });

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncMW', function () {
            $('#syncMW').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            var url = '<?= route('syncMW') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Grade Data from ESS',
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

            }).then(function (result) {
                if (result.value) {
                    $.post(url, function (data) {
                        if (data.status == 200) {
                            $('#syncMW').html('Sync');
                            $('#MWTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            $('#syncMW').html('Sync');
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
