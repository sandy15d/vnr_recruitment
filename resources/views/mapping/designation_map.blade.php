@extends('layouts.master')
@section('title', 'Data Mapping')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Designation Map
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
                                <th>S.No</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Old Designation</th>
                                <th>New Designation</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($old_designation_list as $old)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$old->company_code}}</td>
                                    <td>{{$old->DepartmentName}}</td>
                                    <td>{{$old->DesigName}}</td>
                                    <td>
                                        <select
                                            name="Designation_{{$old->DesigId}}"
                                            id="Designation_{{$old->DesigId}}"
                                            class="form-select form-select-sm" disabled>
                                            <option value="">Select</option>
                                            @foreach($new_designation_list as $row)
                                                <option
                                                    value="{{ $row->id }}"
                                                    {{ isset($mapped_designations[$old->DesigId]) && $mapped_designations[$old->DesigId]->New == $row->id ? 'selected' : '' }}>
                                                    {{ $row->designation_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true"
                                           id="edit_{{$old->DesigId}}" onclick="editDesig({{$old->DesigId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>

                                        <i class="fa fa-save text-success d-inline d-none" aria-hidden="true"
                                           id="save_{{$old->DesigId}}"
                                           onclick="updateDesignation({{$old->DesigId}})"
                                           style="font-size: 20px;cursor: pointer;"></i>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$old_designation_list->links('pagination::custom')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>


        function editDesig(DesigId) {
            $("#Designation_" + DesigId).prop("disabled", false);
            $("#edit_" + DesigId).addClass('d-none');
            $("#save_" + DesigId).removeClass('d-none');

        }

        function updateDesignation(Old) {
            let New = $("#Designation_" + Old).val();
            $.ajax({
                url: "{{ route('mapCoreDesignation') }}",
                type: 'POST',
                data: {
                    Old: Old,
                    New: New,
                },
                dataType: 'json',

                success: function (data) {
                    if (data.status === 200) {
                        toastr.success(data.msg);
                        $("#Designation_" + Old).prop("disabled", true);
                        $("#save_" + Old).addClass('d-none');
                        $("#edit_" + Old).removeClass('d-none');

                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    </script>
@endsection
