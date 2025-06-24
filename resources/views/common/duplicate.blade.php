<link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
<table class="table table-bordered" style="width: 80%">
    <thead>
    <tr>
        <th>S.No</th>
        <th>Name</th>
        <th>check</th>
    </tr>
    </thead>
    <tbody>
    @foreach($candidate_list as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{$row->FName}} {{ $row->MName }} {{ $row->LName }}</td>
            <td>
                @php

                    $dup = CheckDuplicate($row->FName, $row->Phone, $row->Email, $row->DOB, $row->FatherName);

                @endphp
                @if ($dup > 1)
                    <span class="badge badge-danger"><a
                                href="{{ route('get_duplicate_record') }}?Fname={{ $row->FName }}&Phone={{ $row->Phone }}&Email={{ $row->Email }}&DOB={{ $row->DOB }}&FatherName={{ $row->FatherName }}"
                                class="text-danger"
                                target="_blank">Duplicate</a></span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{$candidate_list->links('vendor.pagination.custom')}}
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>