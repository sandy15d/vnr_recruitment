@extends('layouts.master')
@section('title', 'Candidate Detail')
@section('PageContent')
    <div class="page-content">
        <div class="row">
            @foreach ($candidate_list as $item)
                <div class="col-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <ul class="personal-info">
                                <li>
                                    @php
                                        $sendingId = base64_encode($item->JAId);
                                    @endphp

                                    <div class="title">Name<span style="float: right">:</span></div>
                                    <div class="text"> <a
                                            href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                            target="_blank">{{ $item->FName }} {{ $item->MName }}
                                            {{ $item->LName }}</a> <span style="float: right"><i
                                                class="bx bx-trash text-danger" style="font-size:20px; cursor: pointer;"
                                                data-id="{{ $item->JCId }}" id="delete_record"></i></span></div>
                                </li>
                                <li>
                                    <div class="title">Applied For<span style="float: right">:</span></div>
                                    <div class="text text-danger">{{ $item->jobtitle ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">Apply Date For<span style="float: right">:</span></div>
                                    <div class="text text-danger">{{ date('d-M-Y', strtotime($item->ApplyDate)) }}</div>
                                </li>

                                <li>
                                    <div class="title">Phone<span style="float: right">:</span></div>
                                    <div class="text">{{ $item->Phone }}</div>
                                </li>

                                <li>
                                    <div class="title">Email<span style="float: right">:</span></div>
                                    <div class="text">{{ $item->Email }}</div>
                                </li>
                                <li>
                                    <div class="title">Aadhaar No.<span style="float: right">:</span></div>
                                    <div class="text">{{ $item->Aadhaar }}</div>
                                </li>
                                @php
                                    $candidate_log = DB::table('candidate_log')
                                        ->where('JCId', $item->JCId)
                                        ->get();
                                @endphp
                                <h6 class="title">Candidate Log</h6>
                                <table class="table table-bordered text-center table-striped " id="CandLogTable">
                                    <thead>
                                        <th>S.No</th>
                                        <th style="width: 20%">Date</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($candidate_log as $item => $value)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ date('d-M-Y', strtotime($value->Date)) }}</td>
                                                <td>{{ $value->Description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).on('click', '#delete_record', function() {
            var JCId = $(this).data('id');
            //confirm and delete
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "{{ route('delete_duplicate_record') }}",
                    type: "POST",
                    data: {
                        JCId: JCId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            alert(data.msg);

                        } else {
                            alert(data.msg);
                            location.reload();
                        }
                    }
                });
            }
        });
    </script>
@endsection
