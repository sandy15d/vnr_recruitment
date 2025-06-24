@extends('layouts.master')
@section('title', 'User Master')
@section('PageContent')
    @php
        $permission = DB::table('permission')->get();

    @endphp
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">User Master</div>
            <div class="ms-auto">
                <button class="btn btn--new btn-sm" id="addUser" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Add
                    New
                </button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-condensed" id="UserTable" style="width: 100%">
                        <thead class="bg-success text-light">
                        <tr>
                            <th class="th-sm">S.No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>User Type</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cngPasswordModal" tabindex="1" area-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" area-label="Close"></button>
                </div>
                <form action="{{ route('cngUserPwd') }}" method="POST" id="changePasswordForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="UId" id="UId">
                                <label for="NewPassword">Password</label>
                                <input type="password" class="form-control" name="NewPassword" id="NewPassword">
                                <span class="text-danger error-text NewPassword_error"></span>
                            </div>
                            <div class="col-6">
                                <label for="CnfPassword">Confirm Password</label>
                                <input type="text" class="form-control" name="CnfPassword" id="CnfPassword">
                                <span class="text-danger error-text CnfPassword_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="ChangePassword">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addUser') }}" method="POST" id="addUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Company">Company</label>
                                    <select id="Company" name="Company" class="form-select">
                                        <option value="" selected disabled>Select Company</option>
                                        @foreach ($company_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text Company_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Employee">Name</label>
                                    <select name="Employee" id="Employee" class="form-control form-select">
                                        <option value="" selected disabled>Select Employee</option>

                                    </select>
                                    <span class="text-danger error-text Employee_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Username">Username</label>
                                    <input type="text" class="form-control" id="Username" name="Username">
                                    <span class="text-danger error-text Username_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Password">Password</label>
                                    <input type="text" class="form-control" id="Password" name="Password">
                                    <span class="text-danger error-text Password_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="UserType">User Type</label>
                                    <select name="UserType" class="form-control form-select">
                                        <option value="" disabled selected>Select</option>
                                        <option value="H">Employee</option>
                                        <option value="R">Recruiter</option>
                                    </select>
                                    <span class="text-danger error-text UserType_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Contact">Contact</label>
                                    <input type="text" class="form-control" id="Contact" name="Contact" readonly>
                                    <span class="text-danger error-text Contact_error"></span>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Email">Email</label>
                                    <input type="text" class="form-control" id="Email" name="Email" readonly>
                                    <span class="text-danger error-text Email_error"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="Status">Status</label>
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
                        <button type="submit" class="btn btn-primary" id="SaveState">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setPermissionModal" tabindex="1" area-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" area-label="Close"></button>
                </div>
                <form action="{{ route('setpermission') }}" method="POST" id="permission_form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="UserId" id="UserId">
                        <div class="row">
                            <div class="col-6"></div>
                            <div class="col-6">
                                <div class="form-check" style="float: right;">
                                    <input type="checkbox" name="checkall" id="checkall">
                                    <label for="checkall" class="form-check-label">Check All</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="permission_div">
                            {{-- @foreach ($permission as $key => $value)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input page" type="checkbox" value="{{ $value->PId }}"
                                            name="page" id="{{ $value->PId }}">
                                        <label class="form-check-label" for="{{ $value->PId }}">
                                            {{ $value->PageName }}
                                        </label>
                                    </div>

                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script_section')
    <script>
        $('#Company').change(function () {
            var CompanyId = $(this).val();
            if (CompanyId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getEmployee') }}?CompanyId=" + CompanyId,

                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $("#Employee").empty();
                            $("#Employee").append('<option>Select Employee</option>');
                            $.each(res, function (key, value) {
                                $("#Employee").append('<option value="' + key + '">' + value +
                                    '</option>');
                            });

                        } else {
                            $("#Employee").empty();
                        }
                    }
                });
            } else {
                $("#Employee").empty();

            }
        });


        //=================================//
        $(document).on('change', '#Employee', function () {
            var EmployeeID = $(this).val();
            $.get('<?= route('getEmployeeDetail') ?>', {
                EmployeeID: EmployeeID
            }, function (data) {
                $('#Contact').val(data.EmployeeDetail.Contact);
                $('#Email').val(data.EmployeeDetail.Email);
            }, 'json');
        });

        $('#addUserForm').on('submit', function (e) {
            e.preventDefault();
            let form = this;
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
                        $('#addUserModal').modal('hide');
                        $('#UserTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#UserTable').DataTable({
            processing: true,
            info: true,
            searching: true,

            ajax: "{{ route('getAllUser') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
            },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'Username',
                    name: 'Username'
                },
                {
                    data: 'UserType',
                    name: 'UserType',

                },
                {
                    data: 'Contact',
                    name: 'Contact',

                },
                {
                    data: 'email',
                    name: 'email',

                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },

            ],

        });


        // ?==============Delete State======================//
        $(document).on('click', '#deleteBtn', function () {
            var UserId = $(this).data('id');
            var url = '<?= route('deleteUser') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Delete</b> this User',
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
                        UserId: UserId
                    }, function (data) {
                        if (data.status == 200) {
                            $('#UserTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });

        //*****==============================Change Password**********//
        $(document).on('click', '.cngpwd', function () {
            var UId = $(this).data('id');
            $("#UId").val(UId);
            $('#cngPasswordModal').modal('show');
        });


        $('#changePasswordForm').on('submit', function (e) {

            e.preventDefault();
            var form = this;
            var Password = $('#NewPassword').val();
            var ConfirmPassword = $('#CnfPassword').val();
            if (Password != ConfirmPassword) {
                $('.CnfPassword_error').text('Password and Confirm Password not match');
            } else {
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
                            $('#cngPasswordModal').modal('hide');
                            toastr.success(data.msg);
                        }
                    }
                });
            }
        });


        $(document).on('click', '.setpermission', function () {
            var UId = $(this).data('id');
            $("#UserId").val(UId);
            $.ajax({
                //get permission
                url: "{{ route('getPermission') }}",
                type: "POST",
                data: {
                    UserId: UId
                },
                success: function (res) {
                    if (res) {
                        $("#permission_div").empty();
                        var x = '';
                        $.each(res.Permission, function (key, value) {
                            let y = value.active;

                            (y == 'YES') ? y = 'checked' : y = '';
                            x +=
                                '<div class="col-4"><div class="form-check"> <input class="form-check-input page" type="checkbox" value="' +
                                value.PId + '" name="page" id="' + value.PId +
                                '" ' + y + '><label class="form-check-label" for="" >' + value
                                    .PageName + ' </label></div></div>';


                        });

                        $("#permission_div").append(x);

                    } else {
                        $("#permission_div").empty();
                    }
                }
            });
            $('#setPermissionModal').modal('show');
        });

        $('#checkall').click(function () {
            if ($(this).prop("checked") == true) {
                $('.page').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.page').prop("checked", false);
            }
        });

        $('#permission_form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            var permission = [];
            $("input[name='page']").each(function () {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    permission.push(value);
                }
            });
            var UserId = $('#UserId').val();
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: {
                    permission: permission,
                    UserId: UserId
                },

                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $('#setPermissionModal').modal('hide');
                        toastr.success(data.msg);
                    }
                }
            });
        });

        function editstatus(id) {
            $('#Status' + id).prop("disabled", false);
        }

        function changeStatus(id) {
            var status = $('#Status' + id).val();
            var url = '<?= route('changeUserStatus') ?>';
            $.post(url, {
                id: id,
                status: status
            }, function (data) {
                if (data.status == 200) {
                    $('#UserTable').DataTable().ajax.reload(null, false);
                    toastr.success(data.msg);
                } else {
                    toastr.error(data.msg);
                }
            }, 'json');
        }
    </script>
@endsection
