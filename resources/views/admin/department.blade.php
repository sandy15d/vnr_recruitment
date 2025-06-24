@extends('layouts.master')
@section('title', 'Department Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Department</div>

        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="departmenttable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <th class="th-sm">S.No.</th>
                                <th>Department Name</th>
                                <th>Department Code</th>
                                <th>Status</th>
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
        $('#departmenttable').DataTable({
            pageLength: 25,
            processing: true,
            paging: false,
            info: true,
            ajax: "{{ route('getAllDepartment') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'department_code',
                    name: 'department_code'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    'className': 'text-center'
                }
            ],

        });


    </script>
@endsection
