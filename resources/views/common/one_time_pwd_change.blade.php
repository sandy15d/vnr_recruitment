<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/mystyle.css"/>
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/hr.png" type="image/png"/>
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet"/>
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/custom.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">

    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/dark-theme.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/semi-dark.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/header-colors.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/BsMultiSelect.css"/>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/datatable/css/dataTablesButtons.css" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/b0b5b1cf9f.js" crossorigin="anonymous"></script>
    <script src="{{ URL::to('/') }}/assets/ckeditor/ckeditor.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <title>@yield('title')</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
<style>
    .topbar {
        position: fixed;
        top: 0;
        left: 0px;
        right: 0;
        height: 60px;
        background: #fff;
        border-bottom: 1px solid rgb(228 228 228 / 0%);
        z-index: 10;
        -webkit-box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 0px 6px 0 rgb(206 206 238 / 54%);
        box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 0px 6px 0 rgb(206 206 238 / 54%);
    }
</style>
</head>

<body>
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>

            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">

                  {{--  <li class="nav-item dropdown-large">
                        <a id="sidebarsetting" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
                           href="#" role="button"> <i class='bx bx-shape-polygon'></i>
                        </a>
                    </li>--}}


                </ul>
            </div>
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ URL::to('/') }}/assets/images/avatars/avatar-2.png" class="user-img"
                         alt="user avatar">
                    <div class="user-info ps-3">
                        <p class="user-name mb-0"> {{ Auth::user()->name }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              class="d-none">
                            @csrf
                        </form>

                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
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

    <!-- Bootstrap JS -->
    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>

    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>



    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function check() {
            let password = $('#new_password').val();
            let confirm_password = $('#confirm_password').val();
            if (password !== confirm_password) {
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
                let form = this;
                let password = $('#new_password').val();
                let confirm_password = $('#confirm_password').val();
                if(password == '' || confirm_password==''){
                    toastr.error("Please enter Password and Confirm Password..!!");
                    return false;
                }
                if (password === confirm_password) {
                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: new FormData(form),
                        processData: false,
                        dataType: 'json',
                        contentType: false,
                        success: function(data) {
                            if (data.status === 400) {
                                toastr.error(data.msg);
                            } else {
                                $(form)[0].reset();
                                toastr.success(data.msg);
                                window.location.href ="{{route('hod.dashboard')}}";

                            }
                        }
                    });
                } else {
                    toastr.error('Password not match');
                }
            });
        });
    </script>
</body>
</html>
