@extends('layouts.master')
@section('title', 'User Department Map')
@section('PageContent')

    <style>
        .selected {
            background-color: blue;
            color: white;
        }
    </style>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="table-data">
                    <table class="table table-bordered table-striped" id="data-table" style="width: 100%">
                        <thead class="table-success">
                        <tr style="text-align: center">
                            <th>Location</th>
                            @foreach ($departments as $department)
                                <th>{{ $department->department_name }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($user_list as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                @foreach ($departments as $department)
                                    <td class="text-center">
                                        <input type="checkbox" name="map_dept_user" id=""
                                               onclick="user_department_map({{ $user->id }},{{ $department->id }})" {{in_array($user->id.'+'.$department->id, $user_department, true)?'checked':''}}>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>


            </div>
        </div>
    </div>
@endsection
@section('script_section')

    <script>
        $(document).ready(function () {

            var dTable = $('#data-table').DataTable({
                scrollY: 600,
                scrollX: true,

                paging: false,
                info: false,
                ordering: false,

            });
            $('#data-table tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    dTable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
        });

        function user_department_map(UserId, DeptId) {
            $.ajax({
                url: "{{route('map_user_department')}}",
                type: "POST",
                data: {
                    DeptId: DeptId,
                    UserId: UserId,

                },
                success: function (response) {
                    if (response.status !== 200) {
                        toastr.error("Something Went Wrong.. Please Try Again");
                    }
                }
            });
        }
    </script>
@endsection
