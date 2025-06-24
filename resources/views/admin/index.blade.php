<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@php
    $recruiter1 = \App\Models\User::where('role', 'R')->where('Status', 'A')->pluck("name", "id");
@endphp
@extends('layouts.master')
@section('title', 'Dashboard')
@section('PageContent')
    <div class="page-content">
        <style>
            .s0 {
                opacity: .05;
            }

            .table-card {
                margin: -1rem -1rem;
            }

            .align-middle {
                vertical-align: middle !important;
            }

            table {
                caption-side: bottom;
                border-collapse: collapse;
            }

            table td, table th {
                font-size: 14px;
            }

            .card-height-100 {
                height: calc(100% - 1.5rem);
            }

            .card-animate {
                -webkit-transition: all .4s;
                transition: all .4s;
            }

            .card-animate:hover {
                -webkit-transform: translateY(calc(-1.5rem / 5));
                transform: translateY(calc(-1.5rem / 5));
                -webkit-box-shadow: 0 5px 10px rgba(30, 32, 37, .12);
                box-shadow: 0 5px 10px rgba(30, 32, 37, .12);
            }

            .fw-medium {
                font-weight: 500 !important;
            }
        </style>

        <div class="row">
            <div class="col-xl-3 col-md-3">
                <div class="card card-animate overflow-hidden">
                    <div class="position-absolute start-0" style="z-index: 0;">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                             width="200" height="120">
                            <path class="s0"
                                  d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                  fill="#ff0000"></path>
                        </svg>
                    </div>
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class=" fw-medium  text-wrap mb-3">Total Candidate</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5>{{$TotalCandidate}}
                                </h5>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->

            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <a href="/admin/mrf">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>

                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">MRF : Approval
                                        Pending</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{ $NewMRF }}</h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <a href="/admin/active_mrf">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>
                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">Active MRF's</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{ $ActiveMRF }} </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <div class="card card-animate overflow-hidden">
                    <div class="position-absolute start-0" style="z-index: 0;">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                             width="200" height="120">
                            <path class="s0"
                                  d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                  fill="#ff0000"></path>
                        </svg>
                    </div>
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class=" fw-medium text-muted text-wrap mb-3">Open Position</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5>{{$remainingPositions}}
                                </h5>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!--end col-->
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-3">
                <div class="card card-animate overflow-hidden">
                    <div class="position-absolute start-0" style="z-index: 0;">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                             width="200" height="120">
                            <path class="s0"
                                  d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                  fill="#ff0000"></path>
                        </svg>
                    </div>
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class=" fw-medium text-muted text-wrap"> Total Applicants</p>
                                <small>(Against Active MRF)</small>
                            </div>
                            <div class="flex-shrink-0">
                                <h5>{{$TotalApplicants}}
                                </h5>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <a href="/TechnicalScreening">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>
                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">Tech. Screening:
                                        Pending</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{ $pending_tech_scr }}</h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <div class="card card-animate overflow-hidden">
                    <div class="position-absolute start-0" style="z-index: 0;">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                             width="200" height="120">
                            <path class="s0"
                                  d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                  fill="#ff0000"></path>
                        </svg>
                    </div>
                    <div class="card-body" style="z-index:1 ;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="fw-medium text-muted text-wrap">Selected Applicants</p>
                                <small>(Against Active MRF)</small>
                            </div>
                            <div class="flex-shrink-0">
                                <h5>{{ $SelectedCandidate }}</h5>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->

            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <a href="/upcoming_interview">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>
                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">Interview Schedule</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{$upcomming_interview}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-3">
                <a href="/offer_letter?Status=Pending">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>
                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">OL Status : Pending</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{$OLPending}}</h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
            <div class="col-xl-3 col-md-3">
                <a href="/candidate_joining">
                    <div class="card card-animate overflow-hidden">
                        <div class="position-absolute start-0" style="z-index: 0;">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120"
                                 width="200" height="120">
                                <path class="s0"
                                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4"
                                      fill="#ff0000"></path>
                            </svg>
                        </div>
                        <div class="card-body" style="z-index:1 ;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class=" fw-medium text-muted text-wrap mb-3">Upcoming Joinings</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5>{{$upcommingJoining}}
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </a>
            </div><!--end col-->
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card card-height-100">
                    <div class="card-header">
                        <h6>Active Recruiters</h6>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap align-middle table-sm">
                                <thead class="table-primary text-muted">
                                <tr class="text-center">
                                    <th class="text-left">Recruiter</th>
                                    <th>Active MRF</th>
                                    <th>Total Position</th>
                                  {{--  <th>Filled Position</th>--}}
                                    <th>Interviews</th>
                                    <th>Job Offered</th>
                                    <th>Offer Accepted</th>
                                    <th>Joined</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recruiter_tasks as $recruiter)
                                    <tr>
                                        <td class="text-muted">
                                            {{ucwords($recruiter['name'])}}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{$recruiter['Active']}}
                                        </td>
                                        <td class="text-center text-muted">{{$recruiter['total_position']}}</td>
                                       {{-- <td class="text-center text-muted">{{$recruiter['filled_position']}}</td>--}}
                                        <td class="text-center text-muted">{{$recruiter['interview']}}</td>
                                        <td class="text-center text-muted">{{$recruiter['job_offered']}}</td>
                                        <td class="text-center text-muted">{{$recruiter['offer_accepted']}}</td>
                                        <td class="text-center text-muted">{{$recruiter['joined']}}</td>
                                    </tr><!-- end tr -->
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div> <!-- .card-->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 0.5rem">
                    <div class="card-body">
                        <p class="text-center mb-0 fw-bold">MRF Status-Open Days</p>
                        <form action="">
                            <div class="row mb-4">
                                <div class="col-sm-8">
                                    <select name="select_recruiter" id="select_recruiter"
                                            class="form-select form-select-sm"
                                            onchange="getMRFOpenDayChart();">
                                        <option value="">Select Recruiter</option>
                                        @foreach ($recruiter1 as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </form>
                        <div id="columnchart_values" style=" height:300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card" style="margin-bottom: 0.5rem">
                    <div class="card-body">
                        <p class="text-center mb-0 fw-bold">Active Pipeline</p>
                        <form action="">
                            <div class="row mb-4">
                                <div class="col-sm-8">
                                    <select name="select_mrf" id="select_mrf" class="form-select form-select-sm"
                                            onchange="getActiveMrfPipeline();">
                                        <option value="">Select MRF</option>
                                        @foreach ($active_mrf as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </form>

                        <div id="active_mrf_chart" style="height: 300px;"></div>

                    </div>
                </div>
                <div class="card" style="margin-bottom: 0.5rem">
                    <div class="card-body">
                        <p class="text-center mb-0 fw-bold">MRF Summary</p>
                        <div id="columnchart_material" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 0.5rem">
                    <div class="card-body">
                        <div id="resumesource_chart" style=" height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="margin-bottom: 0.5rem">
                    <div class="card-body">
                        <div class="col-md-2">
                            <label for="select_mrf_tat" class="form-label fw-bold">Hiring Status - Graph</label>
                            <select name="select_mrf_tat" id="select_mrf_tat" class="form-select form-select-sm"
                                    onchange="getMRFTAT();">
                                <option value="">Select MRF</option>
                                @foreach ($active_mrf as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="chartContainer" style="height: 400px;" class="d-none"></div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>



    <div class="modal" id="taskmodal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:10px;">
                <div class="modal-header">
                    <h6 class="modal-title text-primary ">Task Allocation List (<i id="RecruiterName"></i>)</h6>
                    <p class="download_label d-none">Task Allocation List</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <di6>
                        <table class="table table-striped table-hover table-condensed table-bordered text-center"
                               id="taskTable" style="width: 100%;font-size:inherit !important;">
                            <thead class="bg-primary text-light text-center">
                            <tr>
                                <td class="td-sm">S.No</td>
                                <td>Job Code</td>
                                <td>MRF Allocation Date</td>
                                <td>MRF Status</td>
                                {{--  <td>Days to Close MRF</td> --}}
                            </tr>
                            </thead>
                        </table>
                    </di6>
                </div>
            </div>
        </div>
    </div>

    @php

        @endphp
@endsection
@section('script_section')
    <script>
        google.charts.load('current', {
            'packages': ['corechart', 'bar']
        });
        // google.charts.setOnLoadCallback(draw_resumesource_chart);
        google.charts.setOnLoadCallback(draw_mrf_summary_chart);
        $(document).on('click', '.viewTask', function () {
            var Uid = $(this).data('id');
            getTaskList(Uid);

        });

        function getTaskList(Uid) {
            getRecruiterName(Uid);
            $('#taskTable').DataTable({
                processing: true,
                info: true,
                searching: false,
                ordering: false,
                lengthChange: true,
                destroy: true,
                dom: 'Bfrtip',
                buttons: [

                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-files-o"></i>',
                        titleAttr: 'Copy',
                        title: $('.download_label').html(),

                    },

                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',
                        title: $('.download_label').html(),
                        exportOptions: {
                            columns: ':visible'

                        }
                    },

                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: 'CSV',
                        title: $('.download_label').html(),

                    },

                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
                        title: $('.download_label').html(),

                    },

                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        title: $('.download_label').html(),


                    },


                ],
                ajax: {
                    url: "{{ route('getTaskList') }}",
                    type: "POST",
                    data: {
                        Uid: Uid
                    },
                    dataType: "JSON",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    },
                    {
                        data: 'AllocatedDt',
                        name: 'AllocatedDt'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    /*  {
                         data: 'days_to_fill',
                         name: 'days_to_fill'
                     } */
                ],

            });
            $('#taskmodal').modal('show');
        }

        function getRecruiterName(Uid) {
            var Uid = Uid;
            $.post('<?= route('getRecruiterName') ?>', {
                Uid: Uid
            }, function (data) {
                $('#RecruiterName').html(data.details);

            }, 'json');
        }

        /*function draw_resumesource_chart() {
              var data = google.visualization.arrayToDataTable([
                  ['ResumeSource', 'total'],
                  @php
            foreach ($resume_source_pie_chart as $d) {
                echo "['" . $d->ResumeSource . "', " . $d->total . '],';
            }
        @endphp
        ]);

        var options = {
            title: 'Total Candidate: {{ $TotalCandidate }}',

              };

              var chart = new google.visualization.PieChart(document.getElementById('resumesource_chart'));
              chart.draw(data, options);
          }*/

        function draw_mrf_summary_chart() {
            var data = google.visualization.arrayToDataTable([
                ['Months', 'Open', 'Closed'],
                @php
                    for ($i = 0; $i < count($mrf_summary_chart); $i++) {
                        echo "['" . $mrf_summary_chart[$i]['Month'] . "', " . $mrf_summary_chart[$i]['Open'] . ', ' . $mrf_summary_chart[$i]['Close'] . '],';
                    }
                @endphp
            ]);

            var options = {
                is3D: true,
                animation: {
                    duration: 5000,
                    easing: 'in',
                    startup: true //This is the new option
                },
                vAxis: {
                    title: 'No. of MRF',
                    titleTextStyle: {
                        color: 'red'
                    },
                    minValue: 0,
                    maxValue: 1000
                },
                chart: {
                    title: 'MRF summary',
                    subtitle: 'Open and Closed MRF in Year {{ date('Y') }}',

                },

            };


            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));

        }

        function getActiveMrfPipeline() {
            var MRFId = $("#select_mrf").val();
            $.ajax({
                type: "POST",
                url: "{{ route('getActiveMRFWiesData') }}",
                data: {
                    MRFId: MRFId
                },
                dataType: "JSON",
                success: function (res) {
                    var chart = new CanvasJS.Chart("active_mrf_chart", {
                        animationEnabled: true,
                        theme: "light2", //"light1", "dark1", "dark2"
                        data: [{
                            type: "funnel",
                            indexLabel: "{label} - {y}",
                            toolTipContent: "<b>{label}</b>: {y} ",
                            neckWidth: 20,
                            neckHeight: 0,
                            valueRepresents: "area",
                            dataPoints: res,
                        }]
                    });

                    chart.render();
                }
            });

        }


        function getMRFOpenDayChart() {
            var UserId = $("#select_recruiter").val();

            $.ajax({
                type: "POST",
                url: "{{ route('mrf_status_open_days') }}",
                data: {
                    UserId: UserId
                },
                dataType: "JSON",
                success: function (res) {
                    var chart2 = new CanvasJS.Chart("columnchart_values", {
                        animationEnabled: true,
                        theme: "light2",
                        axisY: {
                            title: "Days"
                        },
                        data: [{
                            type: "column",
                            showInLegend: true,
                            legendMarkerColor: "grey",
                            legendText: "MRF Status-Open Days",
                            dataPoints: res
                        }]
                    });
                    changeColor(chart2);
                    chart2.render();
                }
            });

        }

        window.onload = function () {
            var chart1 = new CanvasJS.Chart("active_mrf_chart", {
                animationEnabled: true,
                theme: "light2", //"light1", "dark1", "dark2"

                data: [{
                    type: "funnel",
                    indexLabel: "{label} - {y}",
                    toolTipContent: "<b>{label}</b>: {y} ",
                    /*   neckWidth: 10,
                      neckHeight: 10, */
                    valueRepresents: "area",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();


            var chart2 = new CanvasJS.Chart("columnchart_values", {
                animationEnabled: true,
                theme: "light2",
                axisY: {
                    title: "Days"
                },
                data: [{
                    type: "column",
                    showInLegend: true,
                    legendMarkerColor: "grey",
                    legendText: "MRF Status-Open Days",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            changeColor(chart2);
            chart2.render();


            var resume_source_chart = new CanvasJS.Chart("resumesource_chart", {
                theme: "light1",
                exportFileName: "Resume Source Chart",
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: "Resume Source"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "doughnut",
                    innerRadius: 50,
                    showInLegend: true,
                    toolTipContent: "<b>{name}</b>: {y} ",
                    indexLabel: "{name} - {y}",
                    dataPoints: <?php echo json_encode($resume_source_pie_chart_data, JSON_NUMERIC_CHECK); ?>
                }]
            });
            resume_source_chart.render();

            function explodePie(e) {
                if (typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries
                    .dataPoints[e.dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.resume_source_chart.render();
            }
        }

        function changeColor(chart) {
            for (var i = 0; i < chart.options.data.length; i++) {
                for (var j = 0; j < chart.options.data[i].dataPoints.length; j++) {
                    y = chart.options.data[i].dataPoints[j].y;
                    if (y <= 30)
                        chart.options.data[i].dataPoints[j].color = "green";
                    else
                        chart.options.data[i].dataPoints[j].color = "red";
                }
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            let calendarEl = document.getElementById('calendar');
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const day = String(currentDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;

            let calendar = new FullCalendar.Calendar(calendarEl, {
                customButtons: {
                    myCustomButton: {
                        text: 'Add New',
                        click: function () {
                            $("#eventModal").modal('show');
                        }
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek,myCustomButton'
                },

                initialView: 'dayGridMonth',
                initialDate: formattedDate,
                navLinks: true,
                selectable: false,
                nowIndicator: true,
                dayMaxEvents: true,
                editable: false,
                businessHours: true,
                events: @json($events),
                eventDidMount: function (info) {
                    $(info.el).on('click', function () {
                        $(this).tooltip({
                            title: info.event.extendedProps.description,
                        });
                        $(this).tooltip('show'); // Show the tooltip on mouseover
                    });
                },
                eventContent: function (info) {
                    if (calendar.view.type === 'listWeek') {
                        return {
                            html: `${info.event.extendedProps.description}`
                        };
                    } else {
                        return {
                            //html: `${info.timeText}<br>${info.event.extendedProps.description}`
                            html: `${info.timeText}<br>${info.event.title}`
                        };
                    }
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: true
                },

            });

            calendar.render();
        });
    </script>
    <script>
        window.onload = function () {
            var chart1 = new CanvasJS.Chart("active_mrf_chart", {
                animationEnabled: true,
                theme: "light2", //"light1", "dark1", "dark2"

                data: [{
                    type: "funnel",
                    indexLabel: "{label} - {y}",
                    toolTipContent: "<b>{label}</b>: {y} ",

                    valueRepresents: "area",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();
            getMRFTAT();


            var resume_source_chart = new CanvasJS.Chart("resumesource_chart", {
                theme: "light1",
                exportFileName: "Resume Source Chart",
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: "Resume Source"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "doughnut",
                    innerRadius: 50,
                    showInLegend: true,
                    toolTipContent: "<b>{name}</b>: {y} ",
                    indexLabel: "{name} - {y}",
                    dataPoints: <?php echo json_encode($resume_source_pie_chart_data, JSON_NUMERIC_CHECK); ?>
                }]
            });
            resume_source_chart.render();

            function explodePie(e) {
                if (typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries
                    .dataPoints[e.dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.resume_source_chart.render();
            }
        }

        function getMRFTAT() {
            var MRFId = $("#select_mrf_tat").val();
            $.ajax({
                type: "POST",
                url: "{{ route('getMRFTAT') }}",
                data: {
                    MRFId: MRFId
                },
                dataType: "JSON",
                success: function (res) {
                    $("#chartContainer").removeClass('d-none')
                    var chart2 = new CanvasJS.Chart("chartContainer", {

                        title: {
                            text: "MRF : " + res.job_code,
                            fontSize: 15,
                        },
                        theme: "light2",
                        animationEnabled: true,
                        toolTip: {
                            shared: true,
                            reversed: true
                        },
                        axisY: {
                            title: "Count",
                            /*   suffix: " MW"*/
                        },
                        axisX: {
                            labelAngle: -10 // Change the angle as per your requirement
                        },
                        legend: {
                            cursor: "pointer",
                            itemclick: toggleDataSeries
                        },
                        dataPointWidth: 40,
                        data: [

                            {
                                type: "stackedColumn",
                                name: "CV Received",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "green",
                                dataPoints: res.cv_receive
                            }, {
                                type: "stackedColumn",
                                name: "CV Screening",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "blue",
                                dataPoints: res.resume_screening
                            }, {
                                type: "stackedColumn",
                                name: "HR Screening",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "red",
                                dataPoints: res.hr_screening
                            }, {
                                type: "stackedColumn",
                                name: "Technical Screening",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "orange",
                                dataPoints: res.tech_screening
                            },
                            {
                                type: "stackedColumn",
                                name: "Interview",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "purple",
                                dataPoints: res.interview_arr
                            }, {
                                type: "stackedColumn",
                                name: "Second Round Interview",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "brown",
                                dataPoints: res.second_interview
                            }, {
                                type: "stackedColumn",
                                name: "Job Offered",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "yellow",
                                dataPoints: res.job_offer
                            }, {
                                type: "stackedColumn",
                                name: "Offer Accepted",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "DeepPink",
                                dataPoints: res.offer_accepted
                            }, {
                                type: "stackedColumn",
                                name: "Joined",
                                showInLegend: true,
                                yValueFormatString: "#",
                                indexLabel: "{y}",
                                indexLabelPlacement: "inside",
                                indexLabelFontWeight: "bolder",
                                indexLabelFontColor: "white",
                                color: "Crimson",
                                dataPoints: res.joined
                            }
                        ]
                    });
                    chart2.render();

                    function toggleDataSeries(e) {
                        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                            e.dataSeries.visible = false;
                        } else {
                            e.dataSeries.visible = true;
                        }
                        e.chart.render();
                    }
                }
            });

        }
    </script>
@endsection
