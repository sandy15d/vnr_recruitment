@php

    $query = DB::table('screening')
        ->leftJoin('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
        ->leftJoin('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
        ->whereRaw('FIND_IN_SET(?, ScreeningBy)', [Auth::user()->id])
        ->where('jobpost.Status', 'Open')
        ->whereNull('screening.IntervStatus')
        ->select(
            'jobcandidates.JCId',
            'jobcandidates.Title as NameTitle',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.Resume',
            'jobpost.Title',
            'jobapply.JAId',
            'screening.ScreenStatus',
            'jobapply.RejectRemark',
            'screening.ReSentForScreen',
        )
        ->orderByRaw('screening.ScreenStatus IS NULL, screening.ScreenStatus ASC')
        ->get();

@endphp
@extends('layouts.master')
@section('title', 'Pending for Technical Screeening')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pending for Technical Screeening</div>
        </div>
        <!--end breadcrumb-->
        <div class="card  border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed table-bordered" style="width: 100%">
                        <thead class="bg-primary text-light text-center">
                            <tr>
                                <td class="td-sm">S.No</td>
                                <td>Candidate Name</td>
                                <td>Applied for Post</td>
                                <td>HR Screening Remark</td>
                                <td>Resume Sent Date</td>
                                <td>Resume</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($query as $item => $value)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>{{ $value->NameTitle }} {{ $value->FName }} {{ $value->MName }} {{ $value->LName }}
                                    </td>
                                    <td class="text-center">{{ $value->Title }}</td>
                                    <td style="width:50%">
                                        <p style="white-space: normal">{{ $value->RejectRemark }}</p>
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($value->ReSentForScreen)) }}
                                    </td>
                                    <td class="text-center">

                                                        <a href="{{ url('file-view/Resume/' . $value->Resume) }}"
                                                            class="view-pdf">View</a>


                                        {{-- <a href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#resume_modal" class="btn btn-primary btn-sm"
                                            onclick="show_resume({{ $value->JCId }})">View</a> --}}</td>

                                    <td class="text-center">
                                        <select name="screen_status" id="screen_status{{ $value->JAId }}"
                                            class="form-control form-select form-select-sm  d-inline" disabled
                                            onchange="chng_scr_status({{ $value->JAId }},this.value)"
                                            style="width: 100px; ">
                                            <option value="">Select</option>
                                            <option value="Shortlist"
                                                <?= $value->ScreenStatus == 'Shortlist' ? 'selected' : '' ?>>
                                                Shortlist
                                            </option>
                                            <option value="Reject"
                                                <?= $value->ScreenStatus == 'Reject' ? 'selected' : '' ?>>Reject
                                            </option>
                                        </select>
                                        <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true"
                                            id="edit{{ $value->JAId }}"
                                            onclick="edit_scr_status({{ $value->JAId }},this.value)"
                                            style="font-size: 16px;cursor: pointer;"></i>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="resume_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Resume</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="resume_div">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        function show_resume(id) {
            $.ajax({
                url: "{{ route('show_resume') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "JCId": id
                },
                success: function(data) {
                    $('#resume_div').html(data);
                }
            });
        }
        (function(a) {
            a.createModal = function(b) {
                defaults = {
                    title: "",
                    message: "Your Message Goes Here!",
                    closeButton: true,
                    scrollable: false
                };
                var b = a.extend({}, defaults, b);
                html =
                    '<div class="modal fade custom-modal" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false">';
                html += '<div class="modal-dialog">';
                html += '<div class="modal-content">';
                html +=
                    '<div class="modal-header"><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
                html += '<div class="modal-body ">';
                html += b.message;
                html += "</div>";
                html += '<div class="modal-footer">';
                html += "</div>";
                html += "</div>";
                html += "</div>";
                html += "</div>";
                a("body").prepend(html);
                a("#myModal").modal('show').on("hidden.bs.modal", function() {
                    a(this).remove()
                })
            }
        })(jQuery);
        function edit_scr_status(JAId) {
            $('#screen_status' + JAId).prop('disabled', false);
        }

        function chng_scr_status(JAId, value) {
            var remark = "";

            while (remark === null || remark.trim() === "") {
                remark = prompt("Please Enter Remark");
                if (remark === null) {
                    location.reload(); // Reload the page if the user cancels the prompt.
                    return;
                }

                if (remark.trim() === "") {
                    toastr.error("Please Enter a Remark");
                }
            }

            // Make an AJAX request when a valid remark is provided.
            $.ajax({
                url: "{{ route('change_screen_status') }}",
                type: 'POST',
                data: {
                    JAId: JAId,
                    value: value,
                    remark: remark
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status === 200) {
                        $("#loader").modal('hide');
                        toastr.success(data.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
                $(function() {
            $('.view-pdf').on('click', function() {
                var pdf_link = $(this).attr('href');

                var iframe = '<div class="iframe-container"><iframe src="' + pdf_link + '"></iframe></div>'
                $.createModal({
                    title: 'My Title',
                    message: iframe,
                    closeButton: true,
                    scrollable: false
                });
                return false;
            });
        })
    </script>
@endsection
