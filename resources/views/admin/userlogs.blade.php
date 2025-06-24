@extends('layouts.master')
@section('title', 'User Logs')
@section('PageContent')
    <div class="page-content">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <select name="Year" id="Year" class="form-select form-select-sm" onchange="getLogs();">
                                <option value="">Select Year</option>
                                @for ($i = 2021; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @if (isset($_REQUEST['Year']) && $_REQUEST['Year'] != '')
                                <script>
                                    $('#Year').val('<?= $_REQUEST['Year'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-2">
                            <select name="Month" id="Month" class="form-select form-select-sm" onchange="getLogs();">
                                <option value="">Select Month</option>
                                @foreach ($months as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['Month']) && $_REQUEST['Month'] != '')
                                <script>
                                    $('#Month').val('<?= $_REQUEST['Month'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-2">
                            <select name="User" id="User" class="form-select form-select-sm" onchange="getLogs();">
                                <option value="">Select User</option>
                                @foreach ($user as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['User']) && $_REQUEST['User'] != '')
                                <script>
                                    $('#User').val('<?= $_REQUEST['User'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-1">
                            <button type="reset" class="btn btn-danger btn-sm" id="reset"><i
                                    class="bx bx-refresh"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed" id="logtable" style="width: 100%">
                            <thead class="bg-primary text-light text-center">
                                <th style="width: 5%">S.No</th>
                                <th style="width: 10%">Type</th>
                                <th>Subject</th>
                                <th>Date</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('script_section')
    <script>
        function getLogs() {
            $('#logtable').DataTable().draw(true);
        }
        $(document).on('click', '#reset', function() {
            location.reload();
        });
        $(document).ready(function() {
            $('#logtable').DataTable({
                processing: true,
                serverSide: true,
                info: true,
                searching: false,
                ordering: false,
                lengthChange: false,
                info: true,
                destroy: true,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o text-success"> Export Excel</i>',
                    titleAttr: 'Excel',
                    title: 'User Logs',
                    exportOptions: {
                        modifier: {
                            order: 'index', // 'current', 'applied', 'index',  'original'
                            page: 'all', // 'all',     'current'
                            search: 'none' // 'none',    'applied', 'removed'
                        }
                    }
                }, ],

                ajax: {
                    url: "{{ route('getAllLogs') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.User = $('#User').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },

                ],
            });
        });
    </script>
@endsection
