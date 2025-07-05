@extends('layouts.master')
@section('title', 'Company Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Company</div>

        </div>
        <!--end breadcrumb-->

        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="companyTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                        <tr>
                            <td class="td-sm">S.No.</td>
                            <td>Company Name</td>
                            <td>Company Code</td>
                            <th>GST Number</th>
                            <th>Email</th>
                            <th>Website</th>
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


        $('#companyTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllCompanyData') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                'className': 'text-center'
            },
                {
                    data: 'company_name',
                    name: 'company_name',
                    'className': 'text-left'
                },
                {
                    data: 'company_code',
                    name: 'company_code',
                    className: 'text-center'
                },
                {
                    data: 'gst_number',
                    name: 'gst_number',

                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'website',
                    name: 'website',
                },
            ],

        });




    </script>
@endsection
