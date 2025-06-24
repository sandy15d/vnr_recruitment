@extends('layouts.master')
@section('title', 'Data Mapping')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Department Map
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered text-wrap">
                            <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>JobPost</th>
                                <th>Old Designation</th>
                                <th>New Department</th>
                                <th>Sub Department</th>
                                <th>New Designation</th>
                                <th>Save</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($job_post_list as $jp)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$jp->company_code }}</td>
                                    <td>{{ $jp->DepartmentName }}</td>
                                    <td>{{$jp->JobCode}}</td>
                                    <td>{{$jp->Title}}</td>
                                    <td>
                                        <select
                                            name="DepartmentId_{{$jp->JPId}}"
                                            id="DepartmentId_{{$jp->JPId}}"
                                            class="form-select form-select-sm"
                                            disabled
                                            onchange="getSubDepartment({{$jp->JPId}},this.value);">
                                            <option value="">Select</option>
                                            @foreach($new_department_list as $row)
                                                <option
                                                    value="{{ $row->id }}"
                                                    {{ isset($mapped_jobpost[$jp->JPId]) && $mapped_jobpost[$jp->JPId]->Department == $row->id ? 'selected' : '' }}>
                                                    {{ $row->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="Sub_{{$jp->JPId}}" id="Sub_{{$jp->JPId}}"
                                                class="form-select form-select-sm" disabled>
                                            <option value="">Select</option>
                                            @foreach($sub_department_list as $sub)
                                                <option
                                                    value="{{ $sub->id }}"

                                                    {{ isset($mapped_jobpost[$jp->JPId]) && $mapped_jobpost[$jp->JPId]->SubDepartment == $sub->id ? 'selected' : '' }}>
                                                    {{ $sub->sub_department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select
                                            name="Designation_{{$jp->JPId}}"
                                            id="Designation_{{$jp->JPId}}"
                                            class="form-select form-select-sm" disabled>
                                            <option value="">Select</option>
                                            @foreach($new_designation_list as $row)
                                                <option
                                                    value="{{ $row->id }}"
                                                    {{ isset($mapped_jobpost[$jp->JPId]) && $mapped_jobpost[$jp->JPId]->Designation == $row->id ? 'selected' : '' }}>
                                                    {{ $row->designation_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true"
                                           id="edit_{{$jp->JPId}}" onclick="editDept({{$jp->JPId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>

                                        <i class="fa fa-save text-success d-inline d-none" aria-hidden="true"
                                           id="save_{{$jp->JPId}}"
                                           onclick="updateJobPost({{$jp->JPId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        {{$job_post_list->links('pagination::custom')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        function editDept(DepartmentId) {
            $("#DepartmentId_" + DepartmentId).prop("disabled", false);
            $("#Sub_" + DepartmentId).prop("disabled", false);
            $("#Designation_" + DepartmentId).prop("disabled", false);
            $("#edit_" + DepartmentId).addClass('d-none');
            $("#save_" + DepartmentId).removeClass('d-none');

        }

        function updateJobPost(JPId) {
            let Department = $("#DepartmentId_" + JPId).val();
            let SubDepartment = $("#Sub_" + JPId).val();
            let Designation = $("#Designation_" + JPId).val();
            $.ajax({
                url: "{{ route('mapCoreJobPost') }}",
                type: 'POST',
                data: {
                    JPId: JPId,
                    Department: Department,
                    SubDepartment: SubDepartment,
                    Designation: Designation

                },
                dataType: 'json',

                success: function (data) {
                    if (data.status === 200) {
                        toastr.success(data.msg);
                        $("#DepartmentId_" + JPId).prop("disabled", true);
                        $("#Sub_" + JPId).prop("disabled", true);
                        $("#Designation_" + JPId).prop("disabled", true);
                        $("#save_" + JPId).addClass('d-none');
                        $("#edit_" + JPId).removeClass('d-none');
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function getSubDepartment(JPId, Department) {
            $.ajax({
                url: "{{ route('getSubDepartmentByDepartment') }}",
                type: 'POST',
                data: {
                    Department: Department,
                    _token: "{{ csrf_token() }}"  // Ensure CSRF token is included in the request
                },
                dataType: 'json',
                success: function (data) {
                    // Get the dropdown element for the given JPId
                    var $dropdown = $("#Sub_" + JPId);

                    // Empty the current options and add the default "Select" option
                    $dropdown.empty().append('<option value="">Select</option>');

                    // Append new options to the dropdown based on the data
                    $.each(data.sub_departments, function (index, sub_department) {
                        // Sanitize values before appending them
                        var id = $('<div>').text(sub_department.id).html();
                        var name = $('<div>').text(sub_department.sub_department_name).html();

                        $dropdown.append(
                            "<option value='" + id + "'>" + name + "</option>"
                        );
                    });
                },

                error: function (xhr, status, error) {
                    console.error("Error fetching sub-departments:", error);
                    // Optionally handle error (e.g., show a message to the user)
                }
            });
        }

    </script>
@endsection
