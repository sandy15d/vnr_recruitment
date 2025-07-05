@extends('layouts.master')
@section('title', 'Change Password')
@section('PageContent')
    <div class="wrapper">
        <div class="authentication-reset-password d-flex align-items-center justify-content-center" style="height:78vh;">
            <div class="card">
                <div class="row g-0">
                    <div class="col-lg-12 border-end">
                        <div class="card-body">
                            <form action="{{ route('passwordChange') }}" method="POST" id="changepasswordform">
                                <div class="p-5">
                                    <h4 class="font-weight-bold">Genrate New Password</h4>
                                    <div class="mb-3 mt-5">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" placeholder="Enter new password"
                                            name="new_password" id="new_password">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" placeholder="Confirm password"
                                            name="confirm_password" id="confirm_password" onkeyup="check();">
                                        <span id="msg" class="fw-bold text-danger"></span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script_section')
    <script>
        function check() {
            var password = $('#new_password').val();
            var confirm_password = $('#confirm_password').val();
            if (password != confirm_password) {
                $('#confirm_password').addClass('errorfield');
                $('#msg').html('Password not match');
            } else {
                $('#confirm_password').removeClass('errorfield');
                $('#msg').html('');
            }
        }
        $(document).ready(function() {
            $('#changepasswordform').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                var password = $('#new_password').val();
                var confirm_password = $('#confirm_password').val();
                if (password == confirm_password) {
                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: new FormData(form),
                        processData: false,
                        dataType: 'json',
                        contentType: false,
                        success: function(data) {
                            if (data.status == 400) {
                                toastr.error(data.msg);
                            } else {
                                $(form)[0].reset();
                                toastr.success(data.msg);
                                setTimeout(() => {
                                    document.getElementById('logout-form').submit();
                                }, 1000);
                            }
                        }
                    });
                } else {
                    toastr.error('Password not match');
                }
            });
        });
    </script>
@endsection
