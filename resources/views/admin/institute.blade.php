@extends('layouts.master')
@section('title', 'Institute Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Institute</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" id="addInstitute" data-bs-toggle="modal"
                        data-bs-target="#addInstituteModal"><i class="fadeIn animated bx bx-plus"></i>Add New Institute
                </button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed" id="EducationTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                        <tr>
                            <td></td>
                            <td class="td-sm">S.No.</td>
                            <td>Education Institute</td>
                            <td>Code</td>
                            <td>Category</td>
                            <td>Type</td>
                            <td>State</td>
                            <td>District</td>
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


    <div class="modal fade" id="addInstituteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addInstitute') }}" method="POST" id="addInstituteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2" style="justify-content: center;">
                            <div class="col-2">
                                <div class="form-check-inline">
                                    <label class="form-check-label text-center">
                                        <input type="radio" name="Institute_Type" class="form-check-input"
                                               value="College/Institute"
                                               style="font-size: 18px;cursor:pointer;" checked> College/Institute
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-check-inline text-center">
                                    <label class="form-check-label">
                                        <input type="radio" name="Institute_Type" class="form-check-input"
                                               value="Board/University"
                                               style="font-size: 18px;cursor:pointer;"> Board/University
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="InstituteName" id="label1">Institute Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="InstituteName"
                                           placeholder="Enter Institute Name">
                                    <span class="text-danger error-text InstituteName_error"></span>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="InstituteCode" id="label2">Institute Code<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="InstituteCode"
                                           placeholder="Education Code">
                                    <span class="text-danger error-text InstituteCode_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="parentDiv">
                            <div class="form-group">
                                <label for="ParentId">Affiliated Board/University <span
                                        class="text-danger">*</span></label>
                                <select name="ParentId" id="ParentId" class="form-select form-select-sm">
                                    <option value="">Select Board/University</option>
                                    @foreach ($university_list as $row)
                                        <option value="{{ $row->InstituteId }}">{{ $row->InstituteName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="State">State<span class="text-danger">*</span></label>
                                    <select name="State" id="State" class="form-control form-select">
                                        <option value="" selected disabled>Select State</option>
                                        @foreach ($state_list as $key => $state)
                                            <option value="{{ $key }}">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text State_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="District">District<span class="text-danger">*</span></label>
                                    <select name="District" id="District" class="form-control form-select">

                                    </select>
                                    <span class="text-danger error-text District_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Category">Category<span class="text-danger">*</span></label>
                                    <select name="Category" class="form-control form-select">
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="Central">Central</option>
                                        <option value="State">State</option>
                                        <option value="Deemed">Deemed</option>
                                        <option value="Private">Private</option>

                                    </select>
                                    <span class="text-danger error-text Category_error"></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Type">Type<span class="text-danger">*</span></label>
                                    <select name="Type" class="form-control form-select">
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="Agri">Agri</option>
                                        <option value="Non-Agri">Non-Agri</option>
                                    </select>
                                    <span class="text-danger error-text Type_error"></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Status">Status<span class="text-danger">*</span></label>
                                    <select name="Status" class="form-control form-select">
                                        <option value="A">Active</option>
                                        <option value="D">Deactive</option>
                                    </select>
                                    <span class="text-danger error-text Status_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveInstitute">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editInstituteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('editInstitute') }}" method="POST" id="editInstituteForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="EId" name="EId">
                        <div class="row mb-2" style="justify-content: center;">
                            <div class="col-2">
                                <div class="form-check-inline">
                                    <label class="form-check-label text-center">
                                        <input type="radio" name="editInstitute_Type" class="form-check-input"
                                               value="College/Institute"
                                               style="font-size: 18px;cursor:pointer;"> College/Institute
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-check-inline text-center">
                                    <label class="form-check-label">
                                        <input type="radio" name="editInstitute_Type" class="form-check-input"
                                               value="Board/University"
                                               style="font-size: 18px;cursor:pointer;"> Board/University
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="editInstituteName" id="editlabel1">Institute Name</label>
                                    <input type="text" class="form-control" name="editInstituteName"
                                           placeholder="Enter Institute Name">
                                    <span class="text-danger error-text editInstituteName_error"></span>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editInstituteCode" id="editlabel2">Institute Code</label>
                                    <input type="text" class="form-control" name="editInstituteCode"
                                           placeholder="Education Code">
                                    <span class="text-danger error-text editInstituteCode_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="editparentDiv">
                            <div class="form-group">
                                <label for="editParentId">Affiliated Board/University <span
                                        class="text-danger">*</span></label>
                                <select name="editParentId" id="editParentId" class="form-select form-select-sm">
                                    <option value="">Select Board/University</option>
                                    @foreach ($university_list as $row)
                                        <option value="{{ $row->InstituteId }}">{{ $row->InstituteName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="editState">State</label>
                                    <select name="editState" id="editState" class="form-control form-select">
                                        <option value="" selected disabled>Select State</option>
                                        @foreach ($state_list as $key => $state)
                                            <option value="{{ $key }}">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text editState_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="editDistrict">District</label>
                                    <select name="editDistrict" id="editDistrict" class="form-control form-select">
                                        @foreach ($district_list as $key => $state)
                                            <option value="{{ $key }}">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text editDistrict_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editCategory">Category</label>
                                    <select name="editCategory" id="editCategory" class="form-control form-select">
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="Central">Central</option>
                                        <option value="State">State</option>
                                        <option value="Deemed">Deemed</option>
                                        <option value="Private">Private</option>

                                    </select>
                                    <span class="text-danger error-text editCategory_error"></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editType">Type</label>
                                    <select name="editType" id="editType" class="form-control form-select">
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="Agri">Agri</option>
                                        <option value="Non-Agri">Non-Agri</option>
                                    </select>
                                    <span class="text-danger error-text editType_error"></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editStatus">Status</label>
                                    <select name="editStatus" id="editStatus" class="form-control form-select">
                                        <option value="A">Active</option>
                                        <option value="D">Deactive</option>
                                    </select>
                                    <span class="text-danger error-text editStatus_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="UpdateEducation">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $("#ParentId,#State").select2({
            dropdownParent: $('#addInstituteModal')
        });
        $("#editParentId,#editState").select2({
            dropdownParent: $('#editInstituteModal')
        });
        $('#State').change(function () {
            var StateId = $(this).val();
            if (StateId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDistrict') }}?StateId=" + StateId,

                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $("#District").empty();
                            $("#District").append('<option>Select District</option>');
                            $.each(res, function (key, value) {
                                $("#District").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });

                        } else {
                            $("#District").empty();
                        }
                    }
                });
            } else {
                $("#District").empty();

            }
        });

        $('#addInstituteForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#addInstituteModal').modal('hide');
                        $('#EducationTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        $('#EducationTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllInstitute') }}",
            columns: [

                {
                    data: 'chk',
                    name: 'chk'
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },
                {
                    data: 'InstituteName',
                    name: 'InstituteName'
                },
                {
                    data: 'InstituteCode',
                    name: 'InstituteCode'
                },
                {
                    data: 'Category',
                    name: 'Category'
                },
                {
                    data: 'Type',
                    name: 'Type'
                },
                {
                    data: 'StateCode',
                    name: 'StateCode',
                    'className': 'text-center'
                },
                {
                    data: 'DistrictName',
                    name: 'DistrictName'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    'className': 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ],

        });
        //===============Get Institute Record for Updation=================
        $(document).on('click', '#editBtn', function () {
            var InstituteId = $(this).data('id');
            $.post('<?= route('getInstituteDetails') ?>', {
                InstituteId: InstituteId
            }, function (data) {
                $('#editInstituteModal').find('input[name="EId"]').val(data.IntituteDetails.InstituteId);
                $('#editInstituteModal').find('input[name="editInstituteName"]').val(data.IntituteDetails
                    .InstituteName);
                $('#editInstituteModal').find('input[name="editInstituteCode"]').val(data.IntituteDetails
                    .InstituteCode);
                $('#editState').val(data.IntituteDetails.StateId);
                $("#editState").select2();
                $('#editParentId').val(data.IntituteDetails.ParentId);
                $("#editParentId,#editState").select2({
                    dropdownParent: $('#editInstituteModal')
                });
                $('#editDistrict').val(data.IntituteDetails.DistrictId);
                $('#editCategory').val(data.IntituteDetails.Category);
                $('#editType').val(data.IntituteDetails.Type);
                $('#editStatus').val(data.IntituteDetails.Status);
                $('#editInstituteModal').find('input[type=radio][name="editInstitute_Type"][value="' + data.IntituteDetails.Institute_Type + '"]').prop('checked', true).trigger('change');

                $('#editInstituteModal').modal('show');
            }, 'json');
        });
        //===============Update Institute Details================================
        $('#editInstituteForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $('#editInstituteModal').modal('hide');
                        toastr.success(data.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);

                    }
                }
            });
        });
        // ?==============Delete Institute======================//
        $(document).on('click', '#deleteBtn', function () {
            var InstituteId = $(this).data('id');
            var url = '<?= route('deleteInstitute') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Delete</b> this Institute',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Delete',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false
            }).then(function (result) {
                if (result.value) {
                    $.post(url, {
                        InstituteId: InstituteId
                    }, function (data) {
                        if (data.status == 200) {
                            $('#EducationTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });

        $('#editState').change(function () {
            var StateId = $(this).val();
            if (StateId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDistrict') }}?StateId=" + StateId,

                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $("#editDistrict").empty();
                            $("#editDistrict").append('<option>Select District</option>');
                            $.each(res, function (key, value) {
                                $("#editDistrict").append('<option value="' + value + '">' +
                                    key +
                                    '</option>');
                            });

                        } else {
                            $("#editDistrict").empty();
                        }
                    }
                });
            } else {
                $("#editDistrict").empty();

            }
        });

        $(document).on('click', '.select_all', function () {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient");
            }
        });

        $('input[type=radio][name=Institute_Type]').change(function () {
            const selectedValue = $(this).val(); // Get the value of the selected radio button
            const isCollege = selectedValue === 'College/Institute';

            // Define labels based on the selection
            const label1Text = isCollege ? "College/Institute Name<span class='text-danger'>*</span>" : "Board/University Name<span class='text-danger'>*</span>";
            const label2Text = isCollege ? "College/Institute Code<span class='text-danger'>*</span>" : "Board/University Code<span class='text-danger'>*</span>";

            // Update labels
            $("#label1").html(label1Text);
            $("#label2").html(label2Text);

            // Show/Hide the parentDiv and update required attribute
            $("#parentDiv").toggleClass('d-none', !isCollege);
            $("#ParentId").prop('required', isCollege);
        });

        $('input[type=radio][name=editInstitute_Type]').change(function () {
            const selectedValue = $(this).val(); // Get the value of the selected radio button
            const isCollege = selectedValue === 'College/Institute';

            // Define labels based on the selection
            const label1Text = isCollege ? "College/Institute Name<span class='text-danger'>*</span>" : "Board/University Name<span class='text-danger'>*</span>";
            const label2Text = isCollege ? "College/Institute Code<span class='text-danger'>*</span>" : "Board/University Code<span class='text-danger'>*</span>";

            // Update labels
            $("#editlabel1").html(label1Text);
            $("#editlabel2").html(label2Text);

            // Show/Hide the parentDiv and update required attribute
            $("#editparentDiv").toggleClass('d-none', !isCollege);
            $("#editParentId").prop('required', isCollege);
        });

    </script>
@endsection
