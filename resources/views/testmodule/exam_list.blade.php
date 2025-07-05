@extends('layouts.master')
@section('title', 'Question Bank')
@section('PageContent')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Exam List</div>
            <div class="ms-auto">
                <a href="{{ route('exam_master.create') }}" class="btn btn-primary btn-sm"><i
                        class="fadeIn animated bx bx-plus"></i>Exam</a>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed" id="que_bank_table" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                <tr class="text-center">
                                    <th>S.No</th>
                                    <th>Exam Name</th>
                                    <th>Test Paper</th>
                                    <th>Exam Time(Each Paper)</th>
                                    <th>Time Reminder</th>
                                    <th>Max Alert</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($exam_list as $list)
                                    <tr>
                                        <td class="text-center">{{$exam_list->firstItem()+$loop->index}}</td>
                                        <td>{{$list->exam_name}}</td>
                                        <td>
                                            @php
                                                $testpaper = explode(",",$list->test_paper);

                                                foreach ($testpaper as $key => $value) {
                                                    echo ' <span class="badge bg-secondary" style="font-size:10px;"> '.get_subject_name($value).' </span>';
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">{{$list->time}}</td>
                                        <td class="text-center">{{$list->time_reminder}}</td>
                                        <td class="text-center">{{$list->max_alert}}</td>
                                        <td>
                                            @if($list->status == 'A')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('exam_master.edit', $list->id) }}"><i class="bx bx-edit text-primary font-22"></i></a>
                                            <a href="javascript:void(0)"><i
                                                    class="bx bx-trash text-danger ml-2 font-22 delete"
                                                    data-id="{{$list->id}}"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$exam_list->links('pagination::custom')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).on('click', '.delete', function () {
            let id = $(this).data('id');
            let url = '{{ route('exam_master.destroy', ':id') }}';
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
            }).then(function (result) {
                if (result.value) {
                    //ajax call delete method
                    $.ajax({
                        type: "DELETE",
                        url: url.replace(':id', id),
                        success: function (data) {
                            if (data.status === 200) {
                                toastr.success(data.message);
                                setTimeout(function () {
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
    </script>
@endsection
