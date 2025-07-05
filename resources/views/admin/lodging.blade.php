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

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm" onchange="GetElg();">
                        <option value="">Select Department</option>
                        @foreach ($department_list as $key => $value)
                            <option value="{{ $key }}"> {{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
                <div class="col-2">

                    <button style="float:right;" class="btn btn-sm btn--red" id="syncELg"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <table class="table table-condensed text-center" id="MyTable" style="width: 100%; margin-right:20px; ">
                    <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <td rowspan="3" style="text-align: center">Sn</td>
                            <td rowspan="3">Dept</td>
                            <td rowspan="3">Grade</td>
                            <td rowspan="3" style="width:200px;">Vertical</td>
                            <td colspan="3">Lodging Entitlement</td>
                            <td colspan="9">Daily Allowance &amp; Others</td>

                        </tr>
                        <tr>
                            <td colspan="3">Category City</td>
                            <td colspan="2">Daily Allowance</td>
                            <td colspan="4">Mobile Handset &amp; Reimbursement</td>
                            <td rowspan="2">Laptop<br>(Rs)</td>
                            <td rowspan="2">
                                Mediclaim<br>Coverage<br>Slabs<br>(Rs)</td>
                            <td rowspan="2">Helth<br>Checkup<br>(Rs)</td>
                        </tr>
                        <tr>
                            <td>A</td>
                            <td>B</td>
                            <td>C</td>
                            <td>OutSide<br>HQ</td>
                            <td>@<br>HQ</td>
                            <td>Normal<br>(Rs)</td>
                            <td>GPRS<br>(Rs)</td>
                            <td>Reimb<br>(Rs)</td>
                            <td>Period</td>
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
                    url: "{{ route('getAllLodging') }}",
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
                        data: 'department_code',
                        name: 'department_code'
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
                        data: 'CategoryA',
                        name: 'CategoryA'
                    },
                    {
                        data: 'CategoryB',
                        name: 'CategoryB'
                    },
                    {
                        data: 'CategoryC',
                        name: 'CategoryC'
                    },
                    {
                        data: 'DA_OutSiteHQ',
                        name: 'DA_OutSiteHQ'
                    },
                    {
                        data: 'DA_InSiteHQ',
                        name: 'DA_InSiteHQ'
                    },
                    {
                        data: 'Mobile',
                        name: 'Mobile'
                    },
                    {
                        data: 'Mobile_WithGPS',
                        name: 'Mobile_WithGPS'
                    }, {
                        data: 'Mobile_Remb',
                        name: 'Mobile_Remb'
                    }, {
                        data: 'Mobile_Remb_Period',
                        name: 'Mobile_Remb_Period'
                    },
                    {
                        data: 'Laptop_Amt',
                        name: 'Laptop_Amt'
                    },
                    {
                        data: 'Mediclaim_Coverage_Slabs',
                        name: 'Mediclaim_Coverage_Slabs'
                    },
                    {
                        data: 'Helth_CheckUp',
                        name: 'Helth_CheckUp'
                    }
                ],
            });


        });
        function GetElg() {
            $('#MyTable').DataTable().draw(true);
        }

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncELg', function() {
            var url = '<?= route('syncELg') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Elegibility Master from ESS',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Synchronize',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },

            }).then(function(result) {
                if (result.value) {
                    $.post(url, function(data) {
                        if (data.status == 200) {
                            $('#MyTable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    </script>
@endsection
