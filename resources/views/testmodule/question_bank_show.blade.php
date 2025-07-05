@extends('layouts.master')
@section('title', 'Question Bank')
@section('PageContent')
    <style>
        .active {
            background-color: greenyellow;
        }

        p {
            margin-bottom: 0px;
        }

        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

        table,
        th,
        td {
            border: 0.25px solid white;
            vertical-align: middle;

        }

        .highlight {
            background-color: yellow;
        }
    </style>

    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Question Bank</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $Subject->subject_name }}</li>
                </ol>
            </nav>
            <div class="ms-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Department</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                            @foreach ($Department as $item)
                                {{ $item->DepartmentCode }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr/>
        <div class="card border-top border-0 border-4 border-danger mb-3 ">
            <div class="card-body" style="padding-top:5px;">
                <div class="col-12 d-flex justify-content-between" style="padding:5px;">
                    <span class="d-inline fw-bold">Filter</span>
                    <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                            class="bx bx-refresh"></i>Reset</span>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <select name="Filter_Type" id="Filter_Type" class="form-select form-select-sm"
                                onchange="get_question();">
                            <option value="">Question Type</option>
                            @foreach ($QuestionType as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-2">
                        <select name="Filter_Level" id="Filter_Level" class="form-select form-select-sm"
                                onchange="get_question();">
                            <option value="">Difficulty Level</option>
                            @foreach ($Level as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-2">
                        <select name="Filter_Suitable" id="Filter_Suitable" class="form-select form-select-sm"
                                onchange="get_question();">
                            <option value="">Suitable For</option>
                            @foreach ($SuitableFor as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-2">
                        <select name="Filter_Status" id="Filter_Status" class="form-select form-select-s"
                                onchange="get_question();">
                            <option value="">Status</option>
                            <option value="A">Active</option>
                            <option value="D">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="QuestionName" id="QuestionName" class="form-control form-control-sm"
                               placeholder="Search by Question" onkeyup="get_question();">
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-top border-0 border-4 border-success mb-3 ">
                    <div class="card-body" style="padding-top:5px;">
                        <table class="table table-condensed" id="candidate_table"
                               style="width: 100%; margin-right:20px; ">
                            <thead class="text-center bg-success bg-gradient text-light">
                            <tr class="text-center">
                                <th rowspan="3">S.no</th>
                                <th rowspan="3" style="width: 30%;">Question</th>
                                <th rowspan="3" style="text-align: center;">Question Type</th>
                                <th colspan="5" class="text-center" style="padding-right: 0;">Answer</th>
                                <th rowspan="3" class="text-center" style="padding-right: 0;">Suitable For</th>
                                <th rowspan="3" style="text-align: center;padding-right: 0;">Difficulty Level</th>
                                <th rowspan="3">Edit</th>
                            </tr>
                            <tr class="text-center">
                                <th colspan="4" style="text-align: center">MCQ</th>
                                <th rowspan="2" style="text-align: center">TF</th>
                            </tr>
                            <tr>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script_section')
    <script>
        function get_question() {
            $("#candidate_table").DataTable().draw(true);
        }

        $(document).on('click', '#reset', function () {
            window.location.href = "{{ url('question_bank', Request::segment(2)) }}";
        });

        $(document).ready(function () {
            $('#candidate_table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                destroy: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: ':visible'

                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },


                ],
                ajax: {
                    url: "{{ route('get_question_bank_questions', Request::segment(2)) }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Type = $('#Filter_Type').val();
                        d.Suitable = $('#Filter_Suitable').val();
                        d.Status = $('#Filter_Status').val();
                        d.Level = $('#Filter_Level').val();
                        d.Question = $('#QuestionName').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'question',
                        name: 'question'
                    },
                    {
                        data: 'question_type',
                        name: 'question_type',
                        className: 'text-center'
                    },
                    {
                        data: 'option_a',
                        name: 'option_a'
                    },
                    {
                        data: 'option_b',
                        name: 'option_b'
                    },
                    {
                        data: 'option_c',
                        name: 'option_c'
                    },
                    {
                        data: 'option_d',
                        name: 'option_d'
                    },
                    {
                        data: 'True_False',
                        name: 'True_False',
                        className: 'text-center'
                    },
                    {
                        data: 'suitable_for',
                        name: 'suitable_for',
                        className: 'text-center'
                    },
                    {
                        data: 'level',
                        name: 'level',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    },

                ],
                createdRow: (row, data, dataIndex, cells) => {

                    if (data['correct_option'] === 'option_a') {
                        $(cells[3]).css('background-color', 'rgb(187 235 215)')
                    }
                    if (data['correct_option'] === 'option_b') {
                        $(cells[4]).css('background-color', 'rgb(187 235 215)')
                    }
                    if (data['correct_option'] === 'option_c') {
                        $(cells[5]).css('background-color', 'rgb(187 235 215)')
                    }
                    if (data['correct_option'] === 'option_d') {
                        $(cells[6]).css('background-color', 'rgb(187 235 215)')
                    }

                }

            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');
                let url = '{{ route('question_bank.destroy', ':id') }}';
                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>Delete</b> this Question',
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
                        //ajax call delete method
                        $.ajax({
                            type: "DELETE",
                            url: url.replace(':id', id),
                            success: function(data) {
                                if (data.status === 200) {
                                    toastr.success(data.message);
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    toastr.error(data.message);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
