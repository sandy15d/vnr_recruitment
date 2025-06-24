@extends('layouts.master')
@section('title', 'Grade Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Grade</div>

        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card  col-lg-6">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed text-center" id="gradetable" style="width: 100%">
                        <thead class="bg-success text-light ">
                        <tr>
                            <td class="td-sm">S.No.</td>
                            <td>Grade</td>
                            <td>Company</td>
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
@endsection

@section('script_section')
    <script>
        $('#gradetable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllGrade') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
                {
                    data: 'grade_name',
                    name: 'grade_name'
                },
                {
                    data: 'company_code',
                    name: 'company_code'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],

        });
    </script>
@endsection
