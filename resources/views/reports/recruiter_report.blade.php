@extends('layouts.master')
@section('title', 'Recruiter Wise Report')
@section('PageContent')
    <div class="page-content">


        <div class="card border-top border-0 border-4 border-danger mb-3 ">
            <div class="card-body" style="padding-top:5px;">

                <div class="row">
                    <div class="col-2">
                        <label for="from_date" class="form-label fw-bold">From Date:</label>
                        <input type="date" class="form-control form-control-sm" name="from_date" id="from_date"
                               required>
                    </div>
                    <div class="col-2">
                        <label for="to_date" class="form-label fw-bold">To Date:</label>
                        <input type="date" class="form-control form-control-sm" name="to_date" id="to_date" required>
                    </div>
                    {{--   <div class="col-2">
                           <label for="" class="form-label fw-bold">Recruiter:</label>
                           <select name="recruiter" id="recruiter" class="form-select form-select-sm">
                               <option value="">Select Recruiter</option>
                               @foreach ($recruiters as $item)
                                   <option value="{{ $item->id }}">{{ $item->name }}</option>
                               @endforeach
                           </select>

                       </div>--}}
                    <div class="col-2">
                        <button class="btn btn-primary btn-sm mt-4" id="search"><i class="bx bx-search"></i>Search
                        </button>
                        <button class="btn btn-danger btn-sm mt-4" id="reset"><i class="bx bx-reset"></i>Reset</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-top border-0 border-4 border-success mb-1">
                <div class="card-body">
                    <table class="table table-bordered text-center" id="myTable">
                        <thead class="text-center bg-success bg-gradient text-light">
                        <th scope="col">S.No.</th>

                        <th scope="col">Recruiter</th>
                        <th scope="col">Resume Screening</th>
                        <th scope="col">HR Screening</th>

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
        $(document).ready(function () {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                info: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Export',
                    titleAttr: 'Excel',
                    title: 'Recruiter Report',
                },],
                ajax: {
                    url: "{{ route('get_recruiter_wise_data') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.from_date = $("#from_date").val();
                        d.to_date = $("#to_date").val();
                        /*  d.recruiter = $("#recruiter").val();*/
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
                        data: 'recruiter',
                        name: 'recruiter',
                    },
                    {
                        data: 'total_suitable',
                        name: 'total_suitable',
                        className: 'text-center'
                    },
                    {
                        data: 'total_screening',
                        name: 'total_screening',
                        className: 'text-center'
                    },
                ],
            });


            $(document).on('click', '#search', function () {
                if($('#from_date').val() == '' || $('#to_date').val() == ''){
                    alert('Please select from and to date');
                }else{
                    Filter_Data();
                }

            });
            $(document).on('click', '#reset', function () {
                window.location.reload();
            });
        });


        function Filter_Data() {
            $("#myTable").DataTable().draw(true);
        }
    </script>
@endsection
