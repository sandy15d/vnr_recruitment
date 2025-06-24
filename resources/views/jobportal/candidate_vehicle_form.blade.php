@php
    $JCId = base64_decode(request()->query('jcid'));

    $check_vehicle = \Illuminate\Support\Facades\DB::table('jobcandidates')->where('JCId',$JCId)->value('vehicle_form_submit');

@endphp
    <!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/favicon-32x32.png" type="image/png"/>
    <!--plugins-->
    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">

    <title>Job Apply</title>
    <style>
        .borderless td,
        .borderless th {
            border: none;
        }

        .table > :not(caption) > * > * {
            padding: 7px 1px;
        }

        .errorfield {
            border: 2px solid #E8290B;
        }
    </style>
</head>


<body>
<!--wrapper-->
<div class="wrapper">
    <div>
        <div class="container-fluid">
            <div class="col-lg-9 mx-auto">
                <div class="col mx-auto">
                    <div class="card">
                        <div class="card-body">
                            @if($check_vehicle =='Y')
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h5 class="">You Have Successfully Submitted Vehicle Information Form</h5>
                                    </div>
                                </div>
                            @else
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h5 class="">Vehicle Information Form</h5>
                                    </div>
                                    <hr style="margin: 10px 0px 10px 0px;">
                                    <p class="text-danger" style="font-size: 14px; margin-bottom:0px;">Note: File Size
                                        must
                                        be less then 2MB</p>

                                    <hr style="margin: 10px 0px 10px 0px;">
                                    <form action="{{route('SaveVehicleForm')}}" method="post" id="vehicle_form">
                                        @csrf
                                        <input type="hidden" id="JCId" name="JCId" value="{{$JCId}}">
                                        <table class="table borderless">
                                            <tr>
                                                <td>Vehicle Type <span class="text-danger">*</span></td>
                                                <td>
                                                    <select class="form-select form-select-sm req_inp"
                                                            name="vehicle_type"
                                                            id="vehicle_type">
                                                        <option value="two_wheeler">2 Wheeler</option>
                                                        <option value="four_wheeler">4 Wheeler</option>
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Vehicle Brand <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm req_inp"
                                                           id="brand"
                                                           name="brand">
                                                    <span class="text-danger error-text brand_error"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Model Name <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm req_inp"
                                                           id="model_name"
                                                           name="model_name">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Model No
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="model_no"
                                                           name="model_no">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Dealer Name</td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="dealer_name"
                                                           name="dealer_name">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Dealer Contact
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="dealer_contact"
                                                           name="dealer_contact">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Purchase Date <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm req_inp"
                                                           id="purchase_date"
                                                           name="purchase_date" autocomplete="off">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Price <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm req_inp"
                                                           id="price"
                                                           name="price">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Registration No <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm req_inp"
                                                           id="registration_no"
                                                           name="registration_no">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Registration Date <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm req_inp"
                                                           id="registration_date" name="registration_date">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bill / Invoice No</td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" id="bill_no"
                                                           name="bill_no">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Upload Invoice
                                                </td>
                                                <td>
                                                    <input type="file" name="invoice" id="invoice"
                                                           class="form-control form-control-sm"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Fuel Type <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="fuel_type" class="form-select form-select-sm"
                                                            id="fuel_type">
                                                        <option value="petrol">Petrol</option>
                                                        <option value="diesel">Diesel</option>
                                                        <option value="cng">CNG</option>
                                                        <option value="ev">EV</option>
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Ownership <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <select name="ownership" id="ownership"
                                                            class="form-select form-select-sm"
                                                            style="width: 100px;">
                                                        <option value="1st">1st</option>
                                                        <option value="2nd">2nd</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Vehicle Photo <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="file" name="vehicle_image" id="vehicle_image"
                                                           class="form-control form-control-sm req_inp"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    RC <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="file" name="rc_file" id="rc_file"
                                                           class="form-control form-control-sm req_inp"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Vehicle Insurance <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="file" name="insurance" id="insurance"
                                                           class="form-control form-control-sm req_inp"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Driving License <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="file" name="driving_license" id="driving_license"
                                                           class="form-control form-control-sm req_inp"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Current Odo meter reading <span class="text-danger">*</span></td>
                                                <td>
                                                    <input type="text" name="current_odo_meter" id="current_odo_meter"
                                                           class="form-control form-control-sm req_inp">
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    Odo Meter <span class="text-danger">*</span>
                                                </td>
                                                <td>
                                                    <input type="file" name="odo_meter" id="odo_meter"
                                                           class="form-control form-control-sm req_inp"
                                                           accept=".jpg,.jpeg,.png,.pdf">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Remarks</td>
                                                <td colspan="4">
                                                <textarea class="form-control form-control-sm" id="remark"
                                                          name="remark"></textarea>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align: right">
                                                    <button type="reset" class="btn btn-danger">Reset</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</div>
<div class="modal" id="loader" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="width:220px;">
        <div class="modal-content" style="border-radius:10px;">

            <div class="modal-body">
                <img alt="" src="{{ URL::to('/') }}/assets/images/loader.gif">
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>

<script>


    function checkRequired() {
        var res = 0;
        $('.req_inp').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }

    function showProFromOrNot() {
        if ($('#Professional').prop("checked") == true) {
            $('#work_exp').removeClass('d-none');
            $('#PresentCompany').addClass('reqinp');
            $('#Designation').addClass('reqinp');
            $('#JobStartDate').addClass('reqinp');
            $('#GrossSalary').addClass('reqinp');
            $('#CTC').addClass('reqinp');

        } else if ($('#Professional').prop("checked") == false) {
            $('#work_exp').addClass('d-none');
            $('#PresentCompany').removeClass('reqinp');
            $('#Designation').removeClass('reqinp');
            $('#JobStartDate').removeClass('reqinp');
            $('#GrossSalary').removeClass('reqinp');
            $('#CTC').removeClass('reqinp');
        }
    }

    $(document).ready(function () {

    });


    $('#vehicle_form').on('submit', function (e) {
        // debugger;
        e.preventDefault();
        var form = this;
        let req_condition = checkRequired();
        if (req_condition === 1) {
            alert('Please fill required field...!');
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
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status === 400) {
                        $("#loader").modal('hide');
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix +
                                '_error').text(val[0]);
                        });

                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        toastr.success(data.msg);
                    }
                }
            });
        }

    });
</script>
</body>

</html>
