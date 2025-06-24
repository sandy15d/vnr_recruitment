@extends('layouts.master')
@section('title', 'Employee Master')
@section('PageContent')

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Employee</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncEmployee"><i class="fadeIn animated bx bx-sync"></i>Sync</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered" id="employeetable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td></td>
                                <td class="td-sm">S.No.</td>
                                <td>Employee Name</td>
                                <td>EmpCode</td>
                                <td>Company</td>
                                <td>Department</td>
                                <td>Designation</td>
                                <td>Grade</td>
                                <td>CTC</td>
                                <td>Reporting To</td>
                                <td>Status</td>
                                <td>DOJ</td>
                                <td>Date of Sepration</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $('#employeetable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllEmployeeData') }}",
            columns: [

                {
                    data: 'chk',
                    name: 'chk'
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'fullname',
                    name: 'fullname'
                },
                {
                    data: 'EmpCode',
                    name: 'EmpCode'
                },
                {
                    data: 'company_code',
                    name: 'company_code'
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'designation_name',
                    name: 'designation_name'
                },
                {
                    data: 'grade_name',
                    name: 'grade_name'
                },
                {
                    data: 'CTC',
                    name: 'CTC'
                },
                {
                    data: 'Reporting',
                    name: 'Reporting'
                },
                {
                    data: 'EmpStatus',
                    name: 'EmpStatus'
                },
                {
                    data: 'DOJ',
                    name: 'DOJ'
                },
                {
                    data: 'DateOfSepration',
                    name: 'DateOfSepration'
                }

            ],

        });

        //===================== Synchonize Company Data from ESS===================
        $(document).on('click', '#syncEmployee', function() {
            var url = '<?= route('syncEmployee') ?>';
            $('#syncEmployee').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Employee Data from ESS',
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
                            $('#syncEmployee').html('Sync');
                            $('#employeetable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);

                        } else {
                            $('#syncEmployee').html('Sync');
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
        $(document).on('click', '.select_all', function() {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });
    </script>
@endsection
