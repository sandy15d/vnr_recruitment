@extends('layouts.master')
@section('title', 'Education Specialization')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Education Specialization</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" id="addEduSpe" data-bs-toggle="modal"
                    data-bs-target="#addEduSpeModal"><i class="fadeIn animated bx bx-plus"></i>Add New Specialization</button>
            </div>
        </div>
        <!--end breadcrumb-->

        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="EduSpeTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>Education</td>
                                <td>Specialization</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addEduSpeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Education Specialization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addEduSpe') }}" method="POST" id="addEduSpeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Specialization">Specialization</label>
                            <input type="text" class="form-control" name="Specialization"
                                placeholder="Enter Specialization">
                            <span class="text-danger error-text Specialization_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="Education">Education</label>
                            <select id="Education" name="Education[]"class="multiple-select" data-placeholder="Choose anything" multiple="multiple">

                                @foreach ($edu_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text Education_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="Status">Status</label>
                            <select name="Status" class="form-control form-select">
                                <option value="A">Active</option>
                                <option value="D">Deactive</option>
                            </select>
                            <span class="text-danger error-text Status_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveEduSpe">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEduSpeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Specialization Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('editEduSpe') }}" method="POST" id="editEduSpeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="SpId" />
                            <label for="editEduSpe">Education</label>
                            <input type="text" class="form-control" name="editEduSpe">
                            <span class="text-danger error-text editEduSpe_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="editEducation">Education Type</label>
                            <select name="editEducation" id="editEducation" class="form-control">
                                @foreach ($edu_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            </select>
                            <span class="text-danger error-text editEducation_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status</label>
                            <select name="editStatus" id="editStatus" class="form-control form-select">
                                <option value="A">Active</option>
                                <option value="D">Deactive</option>
                            </select>
                            <span class="text-danger error-text editStatus_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="UpdateEduSpe">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>

        $('#addEduSpeForm').on('submit', function(e) {
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
                        $('#addEduSpeModal').modal('hide');
                        $('#EduSpeTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        $('#EduSpeTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllEduSpe') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className' : 'text-center'
                },
                {
                    data: 'EducationCode',
                    name: 'EducationCode'
                },
                {
                    data: 'Specialization',
                    name: 'Specialization'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    'className' : 'text-center'

                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ],

        });
        //===============Get District Record for Updation=================
        $(document).on('click', '#editBtn', function() {
            var SpId = $(this).data('id');
            $.post('<?= route('getEduSpeDetails') ?>', {
                SpId: SpId
            }, function(data) {
                $('#editEduSpeModal').find('input[name="SpId"]').val(data.EduSpeDetails.SpId);
                $('#editEduSpeModal').find('input[name="editEduSpe"]').val(data.EduSpeDetails
                    .Specialization);

                $('#editEducation').val(data.EduSpeDetails
                    .EducationId);
                $('#editStatus').val(data.EduSpeDetails.Status);
                $('#editEduSpeModal').modal('show');
            }, 'json');
        });
        //===============Update District Details================================
        $('#editEduSpeForm').on('submit', function(e) {
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
                        $('#editEduSpeModal').modal('hide');
                        // $('#editEduSpeForm').find(form)[0].reset();
                        $('#EduSpeTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        // ?==============Delete District======================//
        $(document).on('click', '#deleteBtn', function() {
            var SpId = $(this).data('id');
            var url = '<?= route('deleteEduSpe') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Delete</b> this Specialization',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Delete',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        SpId: SpId
                    }, function(data) {
                        if (data.status == 200) {
                            $('#EduSpeTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
