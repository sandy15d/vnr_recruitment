@extends('layouts.master')
@section('title', 'FIRO B Report')
@section('PageContent')

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-2 breadcrumb-title ">
                    FIRO B Reports
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-top border-0 border-4 border-success">
                        <div class="card-body">
                       
                            {{-- Search Form --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('Firob_Reports') }}" class="mb-4 mt-2">
                                        <div class="input-group">
                                            <input type="text" name="reference_no" class="form-control form-control-sm me-2" 
                                                placeholder="Search by Reference No" value="{{ request('reference_no') }}" style="width: 200px;">
                                            <button type="submit" class="btn btn-primary me-2 btn-sm">Search</button>
                                            <a href="{{ route('Firob_Reports') }}" class="btn btn-danger">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table class="table  table-condensed" id="report" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                    <th>S.No</th>
                                    <th>Reference No</th>
                                    <th>Candidate Name</th>
                                    <th>Exam Date</th>
                                    <th>Result</th>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($report_list as $item => $value)
                                        <tr>
                                            <td> @php
                                                if (Request::get('page') != null) {
                                                    $y = (Request::get('page') - 1) * 10 + $i;
                                                } else {
                                                    $y = $i;
                                                }
                                                echo $y;

                                            @endphp</td>
                                            <td>{{ $value->ReferenceNo }}</td>
                                            <td>{{ $value->FName }} {{ $value->LName }}</td>
                                            <td>{{ date('d-M-y', strtotime($value->SubDate)) }}</td>
                                            <td>
                                                @php
                                                    $checkFirob = DB::table('firob_user')
                                                        ->where('userid', $value->JCId)
                                                        ->count();
                                                @endphp
                                                @if ($checkFirob == 54)
                                                    <span style="margin-left: 20px; margin-right:10px;"><a
                                                            href="javascript:void(0);"
                                                            onclick='window.open("{{ route('firob_result') }}?jcid={{ $value->JCId }}", "", "width=750,height=900");'>Result
                                                            1</a> </span> | <span style="margin-left: 20px;"><a
                                                            href="javascript:void(0);"
                                                            onclick='window.open("{{ route('firob_result_summery') }}?jcid={{ $value->JCId }}", "", "width=750,height=900");'>Result
                                                            2</a> </span>
                                                @else
                                                    <a href="javascript:void(0);" class="text-danger"
                                                        onclick="delete_firob({{ $value->JCId }});"> <i
                                                            class="fa fa-trash text-danger"></i> Reset..?</a>
                                                @endif

                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- {{ $report_list->appends([])->links('vendor.pagination.custom') }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('script_section')
        <script>
            $(document).ready(function() {
                $('#report').DataTable({});
            });

            function delete_firob(id) {
                var JCId = $('#JCId').val();
                $.ajax({
                    url: '<?= route('deleteFirob') ?>',
                    method: 'POST',
                    data: {
                        JCId: JCId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    },

                });
            }
        </script>
    @endsection
