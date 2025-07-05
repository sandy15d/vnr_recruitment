@extends('layouts.master')
@section('title', 'Subject Master')
@section('PageContent')
    <style>
        .table tbody tr td {
            padding: 0px;
        }
    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Subject Master</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" id="addCountry" data-bs-toggle="modal"
                    data-bs-target="#addSubjectModal"><i class="fadeIn animated bx bx-plus"></i>Add New Subject</button>
            </div>
        </div>

        <hr style="padding: 0px;" />
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed text-center" id="SubjectTable" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                    <tr>
                                        <td class="td-sm">S.No.</td>
                                        <td>Subject</td>
                                        <td>Type</td>
                                        <td>Status</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $subject->subject_name }}</td>
                                            <td>{{ $subject->subject_type }}</td>
                                            <td class="text-center">
                                                @if ($subject->status == 'A')
                                                    Active
                                                @else
                                                    Deactive
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                        class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                                                </a>
                                                <ul class="dropdown-menu" style="">
                                                    <li> <button
                                                            class="dropdown-item btn btn-outline-danger btn-sm map-subject"
                                                            href="javascript:void(0);" data-id={{ $subject->id }}> <i
                                                                class="bx bx-git-compare"></i> Map</button>
                                                    </li>
                                                    <li> <button
                                                            class="dropdown-item btn btn-outline-danger btn-sm edit-subject"
                                                            href="javascript:void(0);" data-id={{ $subject->id }}> <i
                                                                class="bx bx-pencil"></i> Edit</button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('subject_master.destroy', $subject->id) }}"
                                                            method="POST" style="display: inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Are You Sure Want to Delete?')"><i
                                                                    class="bx bx-trash"></i> Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card" id="dptdiv" style="height: 450px;overflow:auto; display:none">
                    <div class="card-body">
                        <form action="{{ route('map_sub_with_dpt') }}" method="POST" id="mappingform">
                            <input type="hidden" name="subId" id="subId">
                            <table class="table table-bordered" style="width: 100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Department</th>

                                    </tr>
                                </thead>
                                <tbody id="dpttbody">
                                </tbody>
                            </table>
                            <div class="row">
                                <label class="col-sm-8 col-form-label"></label>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary btn-sm">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('subject_master.store') }}" method="POST" id="addSubjectForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="subject_name">Subject Name :</label>
                            <input type="text" class="form-control" name="subject_name" placeholder="Enter Subject Name">
                            <span class="text-danger error-text subject_name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="subject_type">Subject Type :</label>
                            <select name="subject_type" id="subject_type" class="form-select">
                                <option value="">Select</option>
                                <option value="General">General</option>
                                <option value="Functional">Functional</option>
                            </select>
                            <span class="text-danger error-text subject_type_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control form-select">
                                <option value="A">Active</option>
                                <option value="D">Deactive</option>
                            </select>
                            <span class="text-danger error-text status_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveCountry">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Country Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/subject_master" method="POST" id="updateSubjectForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_subject_name">Subject Name :</label>
                            <input type="text" class="form-control" name="edit_subject_name" id="edit_subject_name"
                                placeholder="Enter Subject Name">
                            <span class="text-danger error-text edit_subject_name_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="edit_subject_type">Status :</label>
                            <select name="edit_subject_type" id="edit_subject_type" class="form-control form-select">
                                <option value="General">General</option>
                                <option value="Functional">Functional</option>
                            </select>
                            <span class="text-danger error-text edit_subject_type_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="edit_subject_status">Status :</label>
                            <select name="edit_subject_status" id="edit_subject_status" class="form-control form-select">
                                <option value="A">Active</option>
                                <option value="D">Deactive</option>
                            </select>
                            <span class="text-danger error-text edit_subject_status_error"></span>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="UpdateCountry">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $(document).ready(function() {
            $('#SubjectTable').DataTable();
        });
        $('#addSubjectForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#addSubjectModal').modal('hide');
                        toastr.success(data.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                }
            });


        });

        $(document).on('click', '.edit-subject', function() {
            var id = $(this).data('id');
            $.get('subject_master/' + id + '/edit', function(data) {
                $('#editSubjectModal').modal('show');
                $('#edit_subject_name').val(data.subject.subject_name);
                $('#edit_subject_type').val(data.subject.subject_type);
                $("#edit_subject_status").val(data.subject.status);
                $("#updateSubjectForm").attr('action', 'subject_master/' + id);
            });
        });

        $(document).on('click', '.map-subject', function() {
            var subject_id = $(this).data('id');
            $("#subId").val(subject_id);
            $("#dptdiv").show();
            $.ajax({
                url: "{{ route('get_sub_dept_map') }}",
                type: "POST",
                data: {
                    subject_id: subject_id
                },
                success: function(res) {
                    if (res) {
                        $("#dpttbody").empty();
                        var x = '';
                        $.each(res.Sub_Dept_List, function(key, value) {
                            let y = value.active;
                            (y == 'YES') ? y = 'checked': y = '';
                            x += '<tr><td class="text-center"> <input class="form-check-input dpt" type="checkbox" value="' +
                                value.id + '" name="dpt" id="' + value.id +
                                '" ' + y + '></td><td>&emsp;' + value.department_name +

                                '</td></tr>';
                        });

                        $("#dpttbody").append(x);

                    } else {
                        $("#dpttbody").empty();
                    }
                }
            });
        });

        $('#mappingform').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var department = [];
            $("input[name='dpt']").each(function() {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    department.push(value);
                }
            });
            var subId = $('#subId').val();
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: {
                    department: department,
                    subId: subId
                },
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.message);
                    } else {
                        toastr.success(data.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                }
            });
        });
    </script>
@endsection
