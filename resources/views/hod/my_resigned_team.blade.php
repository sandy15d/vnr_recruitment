@extends('layouts.master')
@section('title', 'My Team')
@section('PageContent')
    <style>
        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">My Resigned Team Details</div>
        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered display compact" id="myteamtable"
                           style="width: 100%">
                        <thead class="text-light bg-success text-center">
                        <tr>
                            <th>#</th>
                            <th class="th-sm">S.No</th>
                            <th class="th-sm">EmpCode</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Grade</th>
                            <th>HQ</th>
                            <th>MRF</th>
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
        $(document).ready(function(){
            $('#myteamtable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: true,
                lengthChange: true,
                info: true,
                ajax: {
                    url: "{{ route('getResignedMember') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Status = $('#Status').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },

                columns: [
                    {
                        data: 'chk',
                        name: 'chk',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        searchable: false
                    },
                    {
                        data: 'EmpCode',
                        name: 'EmpCode',
                        className: 'text-center'

                    },
                    {
                        data: 'fullname',
                        name: 'fullname',

                    },

                    {
                        data: 'company_code',
                        name: 'company_code',
                        className: 'text-center'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                        className: 'text-center'
                    },
                    {
                        data: 'designation_name',
                        name: 'designation_name'
                    },
                    {
                        data: 'GradeValue',
                        name: 'GradeValue',
                        className: 'text-center'
                    },
                    {
                        data: 'HqName',
                        name: 'HqName',
                        className: 'text-center'
                    },

                    {
                        data: 'MStatus',
                        name: 'MStatus',
                        className: 'text-center'
                    }

                ],

            });
        });


        $(document).on('click', '.select_all', function () {
            if ($(this).prop("checked") === true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });
        $(document).on('click', '.addRepMRF', function () {
            var EmployeeID = $(this).data('id');
            window.location.href = "{{ route('repmrf') }}?ei=" + EmployeeID;
        });

        $(document).on('click','#reset',function (){
            window.location.reload();
        });
    </script>
@endsection
