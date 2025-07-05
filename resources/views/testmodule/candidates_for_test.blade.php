@extends('layouts.master')
@section('title', 'Candidate List')
@section('PageContent')
    <style>
        .modal-content {
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }


    </style>
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Candidates</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#import_modal"><i
                        class="fadeIn animated bx bx-file"></i>Import Candidates
                </button>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-top border-0 border-4 border-success mb-1">
                    <div class="card-body d-flex justify-content-between" style="padding: 5px;">
                        <span class="d-inline">
                            <span style="font-weight: bold;">↱</span>
                            <label class="text-success"><input id="checkall" type="checkbox" name="">&nbsp;Check
                                all</label>
                            <i class="text-muted" style="font-size: 13px;">With selected:</i>
                            <label class="text-success " style=" cursor: pointer;" onclick="sendEmail();"><i
                                    class="fas fa-share text-success"></i> Send Email
                            </label>
                        </span>

                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-condensed" id="candidate_table" style="width: 100%">
                                <thead class="bg-success text-light">
                                <tr>
                                    <th></th>
                                    <th>S.No.</th>
                                    <th>Reference No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Exam</th>
                                    <th>Mail Sent</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($candidates as $candidate)
                                    <tr>
                                        <td>
                                            @if($candidate->mail_sent_for_test =='N' && $candidate->exam_id != null)
                                                <input type="checkbox" name="selectCand" class="japchks"
                                                       onclick="checkAllorNot()" value="{{ $candidate->JCId }}">
                                            @endif
                                        </td>
                                        <td>{{$candidates->firstItem() + $loop->index}}</td>
                                        <td>{{$candidate->ReferenceNo}}</td>
                                        <td>{{ $candidate->FName . ' ' . $candidate->MName . ' ' . $candidate->LName }}</td>
                                        <td>{{ $candidate->Phone }}</td>
                                        <td>{{ $candidate->Email }}</td>
                                        <td>
                                            <select name="exam" id="exam_{{$candidate->JCId}}"
                                                    class="form-select form-select-sm d-inline" style="width: 50%"
                                                    disabled onchange="setExam({{$candidate->JCId}},this.value)">
                                                <option value="">Select Exam</option>
                                                @foreach($exam_list as $exam)
                                                    <option
                                                        value="{{$exam->id}}" {{$candidate->exam_id == $exam->id ? 'selected' : ''}}>{{$exam->exam_name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="fa fa-pencil edit-exam_{{$candidate->JCId}}"
                                                  onclick="editExam({{$candidate->JCId}})"
                                                  style="cursor: pointer;"></span>
                                        </td>
                                        <td class="text-center">
                                            @if($candidate->mail_sent_for_test =='Y')
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $candidates->appends([])->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('test_candidate.import') }}" method="POST" enctype="multipart/form-data"
                  id="importForm"
                  style="width: 61%;margin: 0 auto;">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info bg-gradient">
                        <h5 class="modal-title text-white">Import CV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <a href="{{ URL('/') }}/assets/test_candidate_import.xlsx" download
                           class="float-end btn btn-info btn-sm" style="font-size: 15px;">Excel Import Format</a>
                        <br><br>
                        <div id="error_msg" class="text-danger d-none"></div>

                        <p class="fw-bold">
                            Note: For Experience Level, P - Experienced, F - Fresher
                        </p>

                        <p class="fw-bold"> Note: For Resume Source,
                        <ol>
                            <li>1 - Company Careers Site</li>
                            <li>2 - Naukri.Com</li>
                            <li>3 - LinkedIn</li>
                            <li>4 - Walk-in</li>
                            <li>5 - Reference from VNR Employee</li>
                            <li>6 - Placement Agencies</li>
                            <li>7 - Campus</li>
                            <li>8 - Others</li>
                        </ol>
                        </p>

                        <input type="file" name="import_file" class="form-control form-control-sm" accept=".xls,.xlsx">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Import</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#checkall').click(function () {
            if ($(this).prop("checked") == true) {
                $('.japchks').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.japchks').prop("checked", false);
            }
        });

        function checkAllorNot() {
            var allchk = 1;
            $('.japchks').each(function () {
                if ($(this).prop("checked") == false) {
                    allchk = 0;
                }
            });
            if (allchk == 0) {
                $('#checkall').prop("checked", false);
            } else if (allchk == 1) {
                $('#checkall').prop("checked", true);
            }
        }

        $('#importForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $("#loader").css('display', 'block');
                },
                success: function (response) {
                    if (response.status == 200) {
                        $("#loader").css('display', 'none');
                        toastr.success(response.message);
                        window.location.reload();
                    } else {
                        $("#loader").css('display', 'none');

                        var errorMessages = JSON.parse(response.error);
                        var errorHTML = "";
                        for (var rowNumber in errorMessages) {
                            if (errorMessages.hasOwnProperty(rowNumber)) {
                                var rowErrors = errorMessages[rowNumber];
                                var rowErrorMessage = "<b>Row " + rowNumber + " errors:</b><br>";
                                rowErrors.forEach(function (errorMessage) {
                                    rowErrorMessage += errorMessage + "<br>";
                                });
                                errorHTML += rowErrorMessage;
                            }
                        }
                        $("#error_msg").removeClass("d-none").html(errorHTML);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });
        });

        function editExam(id) {
            $('#exam_' + id).prop("disabled", false);
        }

        function setExam(JCId, exam) {
            $.ajax({
                url: "{{ route('setExam') }}",
                type: 'POST',
                data: {
                    JCId: JCId,
                    exam: exam
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        window.location.reload();
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function sendEmail() {
            var JCIds = [];
            $('.japchks').each(function () {
                if ($(this).prop("checked") == true) {
                    JCIds.push($(this).val());
                }
            });
            if (JCIds.length == 0) {
                toastr.error("Please select atleast one candidate");
                return;
            }
            $.ajax({
                url: "{{ route('sendCandidateAssessmentMail') }}",
                type: 'POST',
                data: {
                    JCIds: JCIds
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        window.location.reload();
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    </script>
@endsection
