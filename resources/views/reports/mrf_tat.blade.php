@extends('layouts.master')
@section('title', 'MRF Report')
@section('PageContent')
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-2">
                        <h6 class="text-success">MRF TAT</h6>
                    </div>
                    <div class="col-6">
                        <select name="mrf" id="mrf" class="form-select form-select-sm"
                                onchange="GetMRFDetails();">
                            <option value="">Select MRF</option>
                            @foreach ($active_mrf as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <iframe id="iframe_content" width="100%" height="500px"></iframe>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).on("change", "#mrf", function () {
            let MRFId = $(this).val();
            if (MRFId === '') {
                toastr.error("Please Select MRF");
            }
            $.ajax({
                url: "{{route('get_mrf_tat_data')}}",
                type: "POST",
                data: {
                    MRFId: MRFId,
                },
                beforeSend: function () {
                    $("#loading").css("display", "block");
                },
                success: function (data) {
                    if (data.status === 200) {

                        $("#iframe_content").attr("src", "https://view.officeapps.live.com/op/embed.aspx?wdDownloadButton=True&wdHideSheetTabs=true&ActiveCell=A1&src=" + data.file);
                        setTimeout(function () {
                            $("#loading").css("display", "none");
                        }, 1000);
                    } else {
                        $("#loading").css("display", "none");
                        toastr.error("Something Went Wrong.. Please Try Again Later");
                    }
                }
            });
        });
    </script>
@endsection
