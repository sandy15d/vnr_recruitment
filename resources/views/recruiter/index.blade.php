@extends('layouts.master')
@section('title', 'Dashboard')
@section('PageContent')
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
    <div class="page-content">
        <div class="row">
            <div class="col-xl-6">
                <div class="d-flex flex-column h-100">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
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
                                                <h5>{{ $allocatedmrf }}</h5>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!--end col-->
                        <div class="col-xl-6 col-md-6">
                            <a href="javascript:void(0);">
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
                                                <p class=" fw-medium text-muted text-wrap mb-3">Open Positions</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5>{{$OpenPosition}}</h5>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!--end col-->

                    </div><!--end row-->
                </div>
            </div><!--end col-->
            <div class="col-xl-6">
                <div class="d-flex flex-column">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
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
                                                <p class=" fw-medium text-muted text-wrap mb-3">Upcoming Interview's</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5>{{$upcomming_interview}}</h5>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!--end col-->
                        <div class="col-xl-6 col-md-6">
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
                                                <h5>{{ $PendingJoining }} </h5>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!--end col-->
                    </div>

                </div>

            </div><!--end col-->
        </div>

        <!--end row-->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
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
@endsection
