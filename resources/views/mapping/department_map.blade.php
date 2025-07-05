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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Company</th>
                                <th>Old Department</th>
                                <th>New Department</th>
                                <th>Sub Department</th>
                                <th>Save</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($old_department_list as $dept)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ htmlspecialchars($dept->company_name) }}</td>
                                    <td>{{ htmlspecialchars($dept->DepartmentName) }}</td>
                                    <td>
                                        <select
                                            name="DepartmentId_{{$dept->DepartmentId}}"
                                            id="DepartmentId_{{$dept->DepartmentId}}"
                                            class="form-select form-select-sm"
                                            disabled
                                            onchange="getSubDepartment({{$dept->DepartmentId}},this.value);">
                                            <option value="">Select</option>
                                            @foreach($new_department_list as $row)
                                                <option
                                                    value="{{ $row->id }}"
                                                    {{ isset($mapped_departments[$dept->DepartmentId]) && $mapped_departments[$dept->DepartmentId]->New == $row->id ? 'selected' : '' }}>
                                                    {{ $row->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="Sub_{{$dept->DepartmentId}}" id="Sub_{{$dept->DepartmentId}}"
                                                class="form-select form-select-sm" disabled>
                                            <option value="">Select</option>
                                            @foreach($sub_department_list as $sub)
                                                <option
                                                    value="{{ $sub->id }}"
                                                    {{-- Check if current dept is mapped and set the selected attribute for Sub --}}
                                                    {{ isset($mapped_departments[$dept->DepartmentId]) && $mapped_departments[$dept->DepartmentId]->Sub == $sub->id ? 'selected' : '' }}>
                                                    {{ $sub->sub_department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true"
                                           id="edit_{{$dept->DepartmentId}}" onclick="editDept({{$dept->DepartmentId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>

                                        <i class="fa fa-save text-success d-inline d-none" aria-hidden="true"
                                           id="save_{{$dept->DepartmentId}}"
                                           onclick="updateDepartment({{$dept->CompanyId}},{{$dept->DepartmentId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
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
            $("#edit_" + DepartmentId).addClass('d-none');
            $("#save_" + DepartmentId).removeClass('d-none');

        }

        function updateDepartment(CompanyId, OldDepartment) {
            let NewDepartment = $("#DepartmentId_" + OldDepartment).val();
            let Sub = $("#Sub_" + OldDepartment).val();
            $.ajax({
                url: "{{ route('mapCoreDepartment') }}",
                type: 'POST',
                data: {
                    CompanyId: CompanyId,
                    Old: OldDepartment,
                    New: NewDepartment,
                    Sub: Sub,

                },
                dataType: 'json',

                success: function (data) {
                    if (data.status === 200) {
                        toastr.success(data.msg);
                        $("#DepartmentId_" + OldDepartment).prop("disabled", true);
                        $("#Sub_" + OldDepartment).prop("disabled", true);
                        $("#save_"+OldDepartment).addClass('d-none');
                        $("#edit_"+OldDepartment).removeClass('d-none');
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function getSubDepartment(OldDepartment, Department) {
            $.ajax({
                url: "{{ route('getSubDepartmentByDepartment') }}",
                type: 'POST',
                data: {
                    Department: Department,
                    _token: "{{ csrf_token() }}"  // Ensure CSRF token is included in the request
                },
                dataType: 'json',
                success: function (data) {
                    // Get the dropdown element for the given OldDepartment
                    var $dropdown = $("#Sub_" + OldDepartment);

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
