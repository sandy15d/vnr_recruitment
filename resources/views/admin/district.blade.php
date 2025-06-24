@extends('layouts.master')
@section('title', 'District Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All District</div>
            <div class="ms-auto">
               {{-- <button class="btn btn-primary btn-sm" id="addDistrict" data-bs-toggle="modal"
                    data-bs-target="#addDistrictModal"><i class="fadeIn animated bx bx-plus"></i>Add New District</button>--}}
                <button class="btn btn-sm btn--red" id="syncAPI"><i class="fadeIn animated bx bx-sync"></i>Sync Data</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed" id="DistrictTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No.</td>
                                <td>District Name</td>
                                <td>State</td>
                                <td>Country</td>
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


    <div class="modal fade" id="addDistrictModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addDistrict') }}" method="POST" id="addDistrictForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="DistrictName">District Name</label>
                            <input type="text" class="form-control" name="DistrictName" placeholder="Enter State Name">
                            <span class="text-danger error-text DistrictName_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="State">State</label>
                            <select name="State" class="form-control form-select">
                              <option value="" selected disabled>Select State</option>
                              @foreach ($state_list as $key =>$state)
                                  <option value="{{$key}}">{{$state}}</option>
                              @endforeach
                            </select>
                            <span class="text-danger error-text State_error"></span>
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
                        <button type="submit" class="btn btn-primary" id="SaveDistrict">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDistrictModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update District Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('editDistrict') }}" method="POST" id="editDistrictForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="districtId" />
                            <label for="editDistrict">District</label>
                            <input type="text" class="form-control" name="editDistrict">
                            <span class="text-danger error-text editDistrict_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="editState">State</label>
                           <select id="editState" name="editState" class="form-control form-select">
                               @foreach ($state_list as $state=>$value)
                                   <option value="{{$state}}">{{$value}}</option>
                               @endforeach
                           </select>
                            <span class="text-danger error-text editState_error"></span>
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
                        <button type="submit" class="btn btn-primary" id="UpdateDistrict">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#addDistrictForm').on('submit', function(e) {
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
                    if (data.status==400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#addDistrictModal').modal('hide');
                        $('#DistrictTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#DistrictTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getDistrictList') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },
                {
                    data: 'DistrictName',
                    name: 'DistrictName'
                },
                {
                    data: 'StateName',
                    name: 'StateName'
                },
                {
                    data:'country_name',
                    name:'country_name',
                    'className': 'text-center'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    'className': 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    'className': 'text-center'
                },
            ],

        });
        //===============Get District Record for Updation=================
        $(document).on('click', '#editBtn', function() {
            var DistrictId = $(this).data('id');
            $.post('<?= route('getDistrictDetails') ?>', {
                DistrictId: DistrictId
            }, function(data) {
                $('#editDistrictModal').find('input[name="districtId"]').val(data.DistrictDetails.DistrictId);
                $('#editDistrictModal').find('input[name="editDistrict"]').val(data.DistrictDetails
                    .DistrictName);
                $('#editState').val(data.DistrictDetails.StateId);
                $('#editStatus').val(data.DistrictDetails.Status);
                $('#editDistrictModal').modal('show');
            }, 'json');
        });
        //===============Update District Details================================
        $('#editDistrictForm').on('submit', function(e) {
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
                        $('#editDistrictModal').modal('hide');
                        // $('#editDistrictForm').find(form)[0].reset();
                        $('#DistrictTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        // ?==============Delete District======================//
        $(document).on('click', '#deleteBtn', function() {
            var DistrictId = $(this).data('id');
            var url = '<?= route('deleteDistrict') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Delete</b> this District',
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
                        DistrictId: DistrictId
                    }, function(data) {
                        if (data.status == 200) {
                            $('#DistrictTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });

        $(document).on('click', '#syncAPI', function () {

            $.ajax({
                url: "{{route('sync_district')}}",
                method: 'GET',
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $("#loading").css('display', 'block');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loading").css('display', 'none');
                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        $("#loading").css('display', 'none');
                        toastr.error(data.msg);
                    }
                }
            });

        });
    </script>
@endsection
