@extends('layouts.master')
@section('title', 'Candidate List')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Candidate List - Rejected by HR Screening</div>
        </div>

        <div class="card border-top border-0 border-4 border-danger mb-3 ">
            <div class="card-body" style="padding-top:5px;">
                <div class="col-12 d-flex justify-content-between" style="padding:5px;">
                    <span class="d-inline fw-bold">Filter</span>
                    <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                                class="bx bx-refresh"></i>Reset</span>
                </div>
                <div class="row">

                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="All">All</option>
                            @foreach($department_list as $list)
                                <option value="{{$list->id}}">{{$list->department_name}}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Department']) && $_REQUEST['Department'] !== '')
                            <script>
                                $('#Fill_Department').val('<?= $_REQUEST['Department'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetApplications();">
                            <option value="">Select Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @if (isset($_REQUEST['Year']) && $_REQUEST['Year'] !== '')
                            <script>
                                $('#Year').val('<?= $_REQUEST['Year'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <select name="Month" id="Month" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Month']) && $_REQUEST['Month'] !== '')
                            <script>
                                $('#Month').val('<?= $_REQUEST['Month'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <select name="Recruiter" id="Recruiter" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="">Select Recruiter</option>
                            @foreach ($recruiter_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Recruiter']) && $_REQUEST['Recruiter'] !== '')
                            <script>
                                $('#Recruiter').val('<?= $_REQUEST['Recruiter'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <input type="text" name="Name" id="Name" class="form-control form-control-sm"
                               placeholder="Search by Name" onkeyup="GetApplications();">
                    </div>
                    @if (isset($_REQUEST['Name']) && $_REQUEST['Name'] !== '')
                        <script>
                            $('#Name').val('<?= $_REQUEST['Name'] ?>');
                        </script>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($candidate_list as $candidate)
                <div class="col-md-4">
                    <div class="card border-success border-bottom border-3 border-0">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                    @if ($candidate->CandidateImage == null)
                                        <img src="{{ URL::to('/') }}/assets/images/user1.png"
                                             style="width: 100px; height: 100px;" class="card-img-top" alt="..."/>
                                    @else
                                        <a href="#" class="pop">
                                            <img src="{{ url('file-view/Picture/' . $candidate->CandidateImage) }}"
                                                 style="text-align: center;width: 100px;height: 100px;margin-top: 20px;margin-left: 20px;margin-bottom: 12px;"
                                                 alt="..."/>
                                        </a>
                                    @endif
                                        @php
                                            $sendingId = base64_encode($candidate->JAId);

                                        @endphp
                                        <small>
                                                <span class="text-primary m-1 "
                                                      style="cursor: pointer; font-size:14px;">
                                                     <a href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                                        target="_blank" class="text-primary mt-2 mb-2">View Details</a>
                                                </span>
                                        </small>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless mt-2">
                                    <tr>
                                        <td>
                                            <h6 class="text-success">{{ ucfirst(strtolower($candidate->FName)) }} {{ ucfirst(strtolower($candidate->MName)) }} {{ ucfirst(strtolower($candidate->LName)) }}</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Department</b> :: {{$candidate->DepartmentCode}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Applied For</b> :: {{$candidate->Title}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Apply Date</b> :: {{date('d-m-Y',strtotime($candidate->ApplyDate))}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Screening Date</b> :: {{date('d-m-Y',strtotime($candidate->HrScreeningDate))}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Recruiter</b> :: {{getFullName($candidate->SelectedBy)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                        <div class="card-body">

                            <p class="card-text text-danger"><b>Rejection Remark:</b> {{$candidate->RejectRemark}}</p>

                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        {{ $candidate_list->appends([])->links('vendor.pagination.custom') }}
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
        $(document).ready(function () {

            $(document).on('change', '#Fill_Department', function () {
                GetApplications();
            });
            $(document).on('change', '#Year', function () {
                GetApplications();
            });
            $(document).on('change', '#Month', function () {
                GetApplications();
            })
            $(document).on('change', '#Recruiter', function () {
                GetApplications();
            });
            $(document).on('blur', '#Name', function() {
                GetApplications();
            });
            function GetApplications() {
                var Department = $('#Fill_Department').val() || '';
                var Year = $('#Year').val() || '';
                var Month = $('#Month').val() || '';
                var Name = $('#Name').val() || '';
                var Recruiter = $('#Recruiter').val() || '';
                window.location.href = "{{ route('rejected_candidate') }}?Department=" +
                    Department + "&Year=" + Year + "&Month=" + Month +"&Name=" + Name+"&Recruiter=" + Recruiter;
            }

            $(document).on('click', '#reset', function () {
                window.location.href = "{{ route('rejected_candidate') }}";
            });
        });

    </script>
@endsection
