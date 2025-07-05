@extends('layouts.master')
@section('title', 'Master States (General Purpose)')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All States (General Purpose)</div>
            <div class="ms-auto">

                <button class="btn btn-sm btn--red" id="syncAPI"><i class="fadeIn animated bx bx-sync"></i>Sync Data</button>
            </div>
        </div>
        <!--end breadcrumb-->

        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed table-bordered" id="Statetable"
                        style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>State Name</td>
                                <td>State Code</td>
                                <td>Country</td>
                                <td>Status</td>
                                <td>Action</td>
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


        $('#Statetable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllStateData_General') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },
                {
                    data: 'state_name',
                    name: 'state_name'
                },
                {
                    data: 'state_code',
                    name: 'state_code',
                    'className': 'text-center'
                },
                {
                    data: 'country_name',
                    name: 'country_name',
                    'className': 'text-center'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    'className': 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    'className': 'text-center'
                },
            ],

        });

    </script>
@endsection
