@php

$sendingId = request()->query('jcid');
$JCId = base64_decode($sendingId);
$Rec = DB::table('jobcandidates')
    ->join('trainee_apply', 'trainee_apply.JCId', '=', 'jobcandidates.JCId')
    ->join('jobpost', 'jobpost.JPId', '=', 'trainee_apply.JPId')
    ->select('jobcandidates.*', 'trainee_apply.ApplyDate', 'jobpost.Title as JobTitle', 'jobpost.JobCode')
    ->where('jobcandidates.JCId', $JCId)
    ->first();

@endphp
@extends('layouts.master')
@section('title', 'Trainee Detail')
@section('PageContent')
    <div class="page-content">
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    @if ($Rec->CandidateImage == null)
                                        <img src="{{ URL::to('/') }}/assets/images/user1.png" />
                                    @else
                                        <img src="{{ Storage::disk('s3')->url('Recruitment/Picture/' . $Rec->CandidateImage) }}" />
                                    @endif
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h6 class="user-name m-t-0 mb-0"> {{ $Rec->FName }} {{ $Rec->MName }}
                                                {{ $Rec->LName }}</h6>
                                            <h6 class="staff-id">Applied For: {{ $Rec->JobTitle }}</h6>
                                            <h6 class="staff-id text-primary">MRF: {{ $Rec->JobCode }}</h6>

                                            <div class="staff-id">ReferenceNo : {{ $Rec->ReferenceNo }}</div>
                                            <div class="staff-id">Date of Apply :
                                                {{ date('d-M-Y', strtotime($Rec->ApplyDate)) }}</div>
                                            <div class="staff-msg"><a class="btn btn-custom btn-sm"
                                                    href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#resume_modal">View Resume</a>
                                                <a href="javascript:;" class="btn btn-primary btn-sm compose-mail-btn">Send
                                                    Mail</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Phone:</div>
                                                <div class="text"><a href="#">{{ $Rec->Phone }}</a></div>
                                            </li>
                                            <li>
                                                <div class="title">Email:</div>
                                                <div class="text text-primary">{{ $Rec->Email }}</div>

                                            </li>
                                            <li>
                                                <div class="title">Birthday:</div>
                                                <div class="text  text-dark">{{ date('d-M-Y', strtotime($Rec->DOB)) }}
                                                    (Age:
                                                    {{ \Carbon\Carbon::parse($Rec->DOB)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days') }})
                                                </div>
                                            </li>
                                            <li style="margin-bottom: 0px">
                                                <div class="title">Address:</div>
                                                <div class="text  text-dark">{{ $Rec->AddressLine1 }},
                                                    {{ $Rec->AddressLine2 }}, {{ $Rec->AddressLine3 }}</div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 ">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h6 class="card-title">Personal Informations </h6>
                        <ul class="personal-info">
                            <li>
                                <div class="title">Father<span style="float: right">:</span></div>
                                <div class="text">{{ $Rec->FatherTitle }} {{ $Rec->FatherName }}</div>
                            </li>
                            <li>
                                <div class="title">Gender<span style="float: right">:</span></div>
                                <div class="text">{{ $Rec->Gender == 'M' ? 'Male' : 'Female' }}
                                </div>
                            </li>

                            <li>
                                <div class="title">Aadhaar No.<span style="float: right">:</span></div>
                                <div class="text">{{ $Rec->Aadhaar }}</div>
                            </li>

                            <li>
                                <div class="title">Collage <span style="float: right">:</span></div>
                                <div class="text">{{ getCollegeById($Rec->College) }}</div>
                            </li>

                            <li>
                                <div class="title">Qualification<span style="float: right">:</span></div>
                                <div class="text">{{ getEducationCodeById($Rec->Education) }} -
                                    {{ getSpecializationbyId($Rec->Specialization) }}</div>
                            </li>
                            <li>
                                <div class="title">CGPA<span style="float: right">:</span></div>
                                <div class="text">{{ $Rec->CGPA }}</div>
                            </li>

                            <li>
                                <div class="title">Year of Passing<span style="float: right">:</span></div>
                                <div class="text">{{ $Rec->PassingYear }}</div>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h6 class="card-title">Work Experience </h6>
                        <h6>Experience : {{ $Rec->Professional == 'F' ? 'Fresher' : '' }}</h6>
                        @if ($Rec->Professional == 'P')
                            <ul class="personal-info">
                                <li>
                                    <div class="title">Company</div>
                                    <div class="text">{{ $Rec->PresentCompany ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">Designation</div>
                                    <div class="text">{{ $Rec->Designation ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">Start Date</div>
                                    <div class="text">{{ $Rec->JobStartDate ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">End Date</div>
                                    <div class="text">{{ $Rec->JobEndDate ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">Gross Salary</div>
                                    <div class="text">{{ $Rec->GrossSalary ?? '-' }}</div>
                                </li>

                                <li>
                                    <div class="title">CTC (Annual)</div>
                                    <div class="text">{{ $Rec->CTC ?? '-' }}</div>
                                </li>
                                <li>
                                    <div class="title">Notice Period</div>
                                    <div class="text">{{ $Rec->NoticePeriod ?? '-' }}</div>
                                </li>

                                <li>
                                    <div class="title">Reason for Leaving</div>
                                    <div class="text">{{ $Rec->ResignReason ?? '-' }}</div>
                                </li>
                            </ul>
                        @endif



                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="resume_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Resume</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $resume = $Rec->Resume;
                        $ext = substr($resume, strrpos($resume, '.') + 1);
                    @endphp
                    @if ($ext == 'pdf' || $ext == 'PDF')
                        <object width="760" height="500" data="{{ Storage::disk('s3')->url('Recruitment/Resume/' . $Rec->Resume) }}"
                            id="{{ $Rec->JCId }}"></object>
                    @else
                        @php
                            $url = html_entity_decode('https://docs.google.com/viewer?embedded=true&url=');
                        @endphp
                        <iframe src="{{ $url }}{{ Storage::disk('s3')->url('Recruitment/Resume/' . $Rec->Resume) }}"
                            width="100%" height="500" style="border: none;"></iframe>
                    @endif

                    <div class="row">
                        <div class="col-12" style="float: right">
                            <a href="{{ Storage::disk('s3')->url('Recruitment/Resume/' . $Rec->Resume) }}" target="_blank">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="compose-mail-popup" style="display: none;">
        <div class="card">
            <div class="card-header bg-dark text-white py-2 cursor-pointer">
                <div class="d-flex align-items-center">
                    <div class="compose-mail-title">New Message</div>
                    <div class="compose-mail-close ms-auto">x</div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sendMailToCandidate') }}" method="POST" id="SendMailForm">
                    <div class="email-form">
                        <div class="mb-3">
                            <input type="hidden" name="CandidateName" id="CandidateName"
                                value="{{ $Rec->FName }} {{ $Rec->LName }}">
                            <input type="text" class="form-control" value="{{ $Rec->Email }}" readonly name="eMailId"
                                id="eMailId">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Subject" name="Subject" id="Subject">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="Message" rows="10" cols="10" name="eMailMsg"
                                id="eMailMsg"></textarea>
                        </div>
                        <div class="mb-0">
                            <div style="float: right">
                                <button class="btn btn-primary submit-btn" id="send_mail_btn">Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="overlay email-toggle-btn-mobile"></div>

@endsection

@section('script_section')
    <script>
        $('#SendMailForm').on('submit', function(e) {
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
                    $('#send_mail_btn').html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                },
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('.compose-mail-popup').hide();
                        toastr.success(data.msg);
                    }
                }
            });
        });
    </script>
@endsection
