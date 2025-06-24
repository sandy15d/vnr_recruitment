@extends('layouts.master')
@section('title', 'Core APIs')
@section('PageContent')
    <style>
        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4px !important;
            height: 25px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 550;
        }

        .frmbtn {
            padding: 2px 4px !important;
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
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Core API</div>
            <div class="ms-auto">
                <button class="btn btn-sm btn--red" id="syncAPI"><i class="fadeIn animated bx bx-sync"></i>Sync API
                </button>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <table class="table  table-condensed" id="mytable"
                >
                    <thead class="text-center bg-success bg-gradient text-light">
                    <tr>
                        <th></th>
                        <th>S.No</th>
                        <th>API Name</th>
                        <th>API End Point</th>
                        <th>Parameter</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($api_list as $api)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="apis" id="apis_{{$loop->iteration}}"
                                       value="{{$api->api_end_point}}">
                            </td>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$api->api_name}}</td>
                            <td>{{$api->api_end_point}}</td>
                            <td>{{$api->parameters}}</td>
                            <td>{{$api->description}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <button class="btn btn-sm btn-primary" id="import_btn">Import Data</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

@endsection
@section('script_section')
    <script>
        $(document).on('click', '#syncAPI', function () {

            $.ajax({
                url: "{{route('core_api_sync')}}",
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

                    } else {
                        $("#loading").css('display', 'none');
                        toastr.error(data.msg);
                    }
                }
            });

        });


        $(document).on('click', '#import_btn', function () {
            var api_end_points = [];

            $("input[name='apis']").each(function () {
                if ($(this).prop("checked") === true) {
                    var value = $(this).val();
                    api_end_points.push(value);
                }
            });
            if (api_end_points.length > 0) {
                if (confirm('Are you sure to import selected api data?')) {
                    $.ajax({
                        url: "{{route('importAPISData')}}",
                        type: 'POST',
                        data: {
                            api_end_points: api_end_points,
                        },
                        success: function (data) {
                            if (data.status === 400) {
                                alert("Something went wrong...!!");
                            } else {
                                alert("Data imported successfully...!!");
                            }
                        }
                    });
                }

            } else {
                alert('No API Selected!\nPlease select atleast one api to proceed.');
            }

        });
    </script>
@endsection
