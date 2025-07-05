@extends('layouts.master')
@section('title', 'Resume Source Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Resume Source</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" id="addResumeSource" data-bs-toggle="modal"
                    data-bs-target="#addResumeSourceModal"><i class="fadeIn animated bx bx-plus"></i>Add New Resume Source</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed" id="resumesourcetable"
                        style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>Resume Source</td>
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

    <div class="modal fade" id="addResumeSourceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Resume Source</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addResumeSource') }}" method="POST" id="addResumeSourceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="ResumeSource">Resume Source</label>
                            <input type="text" class="form-control" name="ResumeSource"
                                placeholder="Enter Resume Source Name">
                            <span class="text-danger error-text ResumeSource_error"></span>
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
                        <button type="submit" class="btn btn-primary" id="SaveResumeSource">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editResumeSourceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Resume Source Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('editResumeSource') }}" method="POST" id="editResumeSourceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="RId" />
                            <label for="editResumeSource">State Name</label>
                            <input type="text" class="form-control" name="editResumeSource">
                            <span class="text-danger error-text editResumeSource_error"></span>
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
                        <button type="submit" class="btn btn-primary" id="UpdateResumeSource">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#resumesourcetable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllResumeSource') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className' : 'text-center'
                },
                {
                    data: 'ResumeSource',
                    name: 'ResumeSource'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    'className' : 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],

        });

        $('#addResumeSourceForm').on('submit', function(e) {
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
                        $('#addResumeSourceModal').modal('hide');
                        $('#resumesourcetable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        //===============Get Institute Record for Updation=================
        $(document).on('click', '#editBtn', function() {
            var ResumeSouId = $(this).data('id');
            $.post('<?= route('getResumeSourceDetails') ?>', {
                ResumeSouId: ResumeSouId
            }, function(data) {
                $('#editResumeSourceModal').find('input[name="RId"]').val(data.ResumeSourceDetail.ResumeSouId);
                $('#editResumeSourceModal').find('input[name="editResumeSource"]').val(data.ResumeSourceDetail
                    .ResumeSource);
                $('#editStatus').val(data.ResumeSourceDetail.Status);
                $('#editResumeSourceModal').modal('show');
            }, 'json');
        });
        //===============Update Institute Details================================
        $('#editResumeSourceForm').on('submit', function(e) {
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
                        $('#editResumeSourceModal').modal('hide');
                        $('#resumesourcetable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        // ?==============Delete Institute======================//
        $(document).on('click', '#deleteBtn', function() {
            var ResumeSouId = $(this).data('id');
            var url = '<?= route('deleteResumeSource') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Delete</b> this Resume Source',
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
                        ResumeSouId: ResumeSouId
                    }, function(data) {
                        if (data.status == 200) {
                            $('#resumesourcetable').DataTable().ajax.reload(null, false);
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
