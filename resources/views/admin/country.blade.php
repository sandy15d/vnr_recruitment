@extends('layouts.master')
@section('title', 'Country Master')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Country</div>
        </div>
        <!--end breadcrumb-->

        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed text-center" id="countryTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                        <tr>
                            <td class="td-sm">S.No.</td>
                            <td>Country Name</td>
                            <td>Country Code</td>

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
        $('#countryTable').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('getAllCountryData') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
                {
                    data: 'country_name',
                    name: 'country_name'
                },
                {
                    data: 'country_code',
                    name: 'country_code'
                },


            ],

        });

        $(document).on('click', '#syncAPI', function () {

            $.ajax({
                url: "{{route('sync_country')}}",
                method: 'GET',
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $("#loading").css('display', 'block');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loading").css('display', 'none');
                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        $("#loading").css('display', 'none');
                        toastr.error(data.msg);
                    }
                }
            });

        });

    </script>
@endsection
