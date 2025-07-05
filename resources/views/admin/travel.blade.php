@extends('layouts.master')
@section('title', 'Lodging Eligibility')
@section('PageContent')
    <style>
        .table>:not(caption)>*>* {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4 px !important;
            height: 25 px;
            border-radius: 4 px;
            font-size: 11px;
            font-weight: 550;
        }

        .frmbtn {
            padding: 2 px 4 px !important;
            font-size: 11px;
            cursor: pointer;
        }

        table,
        th,
        td {
            border: 0.25px solid white;
            vertical-align: middle;

        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-4 breadcrumb-title ">
                    Lodging, Daily Allowance & Other
                </div>

                <div class="col-3">

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                        onchange="GetElg();">
                        <option value="">Select Department</option>
                        @foreach ($department_list as $key => $value)
                            <option value="{{ $key }}"> {{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>

            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <table class="table table-condensed text-center" id="MyTable" style="width: 100%; margin-right:20px; ">
                    <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <td class="th" rowspan="4">Sn</td>
                            <td class="th" rowspan="4">Dept</td>
                            <td class="th" rowspan="4">Grade</td>
                            <td class="th" rowspan="4">Vertical</td>
                            <td class="th" colspan="5">Travel Entitlement</td>
                            <td class="th" colspan="6">Travel Eligibility</td>

                        </tr>
                        <tr>
                            <td class="th" colspan="2">Flight</td>
                            <td class="th" colspan="2">Train</td>
                            <td class="th" rowspan="3">Remark</td>
                            <td class="th" colspan="3">Two Wheeler</td>
                            <td class="th" colspan="3">Four Wheeler</td>
                        </tr>
                        <tr>
                            <td class="th" rowspan="2">Allow</td>
                            <td class="th" rowspan="2">Class</td>
                            <td class="th" rowspan="2">Allow</td>
                            <td class="th" rowspan="2">Class</td>

                            <td class="th" rowspan="2">Rs/Km</td>
                            <td class="th" colspan="2">Max. Limit</td>
                            <td class="th" rowspan="2">Rs/Km</td>
                            <td class="th" colspan="2">Max. Limit</td>


                        </tr>
                        <tr>
                            <td class="th"  >Km/<br>Month</td>
                            <td class="th"  >Km/<br>Day</td>
                            <td class="th"  >Km/<br>Month</td>
                            <td class="th"  >Km/<br>Annum</td>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('script_section')

    <script>
        $(document).ready(function() {
            $('#MyTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: false,
                info: true,
                ajax: {
                    url: "{{ route('getAllTravel') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {

                        d.Department = $('#Fill_Department').val();

                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: 'GradeValue',
                        name: 'GradeValue'
                    },
                    {
                        data: 'vertical_name',
                        name: 'vertical_name'
                    },
                    {
                        data: 'Flight_YN',
                        name: 'Flight_YN'
                    },
                    {
                        data: 'Flight_Class',
                        name: 'Flight_Class'
                    },
                    {
                        data: 'Train_YN',
                        name: 'Train_YN'
                    },
                    {
                        data: 'Train_Class',
                        name: 'Train_Class'
                    },
                    {
                        data: 'TravelEnt_Rmk',
                        name: 'TravelEnt_Rmk'
                    },
                    {
                        data: 'TW_Km',
                        name: 'TW_Km'
                    },
                    {
                        data: 'TW_InHQ_M',
                        name: 'TW_InHQ_M'
                    }, {
                        data: 'TW_InHQ_D',
                        name: 'TW_InHQ_D'
                    },
                    {
                        data: 'FW_Km',
                        name: 'FW_Km'
                    },
                    {
                        data: 'FW_InHQ_M',
                        name: 'FW_InHQ_M'
                    },
                    {
                        data: 'FW_InHQ_D',
                        name: 'FW_InHQ_D'
                    },


                ],
            });


        });
        function GetElg() {
            $('#MyTable').DataTable().draw(true);
        }


    </script>
@endsection
