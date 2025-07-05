@extends('layouts.master')
@section('title', 'HQ & Vetical Wise Region Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->


        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-4 breadcrumb-title ">
                    All regions
                </div>

                <div class="col-3">

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="GetElg();">
                        <option value="">Select Department</option>
                        @foreach ($department_list as $key => $value)
                            <option value="{{ $key }}"> {{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
                <div class="col-2">

                    <button style="float:right;" class="btn btn-sm btn--red" id="syncHqRegion"><i
                            class="fadeIn animated bx bx-sync"></i>Sync
                    </button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed" id="mytable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                        <tr>
                            <td class="td-sm">S.No.</td>
                            <td>Department Name</td>
                            <td>Headquarter</td>
                            <td>Vertical Name</td>
                            <td>Region Name</td>
                            <td>Zone Name</td>
                            <td>Status</td>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#mytable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,
            lengthChange: true,
            info: true,
            ajax: {
                url: "{{ route('getAllRegionHq') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {

                    d.Department = $('#Fill_Department').val();

                },
                type: 'POST',
                dataType: "JSON",
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                'className': 'text-center'
            },
                {
                    data: "DepartmentName",
                    name: "DepartmentName"
                },
                {
                    data: 'HqName',
                    name: 'HqName'
                },
                {
                    data: 'VerticalName',
                    name: 'VerticalName'
                }, {
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
                    'className': 'text-center'
                }
            ],

        });

        function GetElg() {
            $('#mytable').DataTable().draw(true);
        }

        $(document).on('click', '#reset', function() {
            location.reload();
        });
        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncHqRegion', function () {
            var url = '<?= route('syncHqRegion') ?>';
            $('#syncHqRegion').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Region HQ and Vertical Data from ESS',
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
                            $('#mytable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                            $('#syncHqRegion').html('Sync');
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
