@extends('layouts.master')
@section('title', 'Communication Control')
@section('PageContent')
    @php
    $query = DB::table('communication_control')->get();

    @endphp
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Communication Control</div>

        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed" id="myTable" style="width: 100%">
                        <thead class="bg-success text-light text-center">
                            <tr>
                                <td class="td-sm">S.No</td>
                                <td>Communication Topic</td>
                                <td>Sender</td>
                                <td>Receiver</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $item)
                                <tr >
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-left">{{ $item->title }}</td>
                                    <td class="text-center">{{ $item->sender }}</td>
                                    <td class="text-center">{{ $item->receiver }}</td>
                                    <td class="text-center"><input class="commCtrl" type="checkbox" data-toggle="toggle" data-on="Active"
                                            data-off="Deactive" data-onstyle="success" data-offstyle="danger" data-size="sm"
                                            data-id="{{ $item->id }}" <?= $item->is_active == '1' ? 'checked' : '' ?>>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $(document).on("change", ".commCtrl", function() {
            var id = $(this).data('id');
            $.post('<?= route('setCommunication') ?>', {
                id: id
            }, function(data) {
            }, 'json');
        });
    </script>
@endsection
