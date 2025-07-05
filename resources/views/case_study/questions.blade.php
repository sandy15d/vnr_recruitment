@extends('layouts.master')
@section('title', 'Case Study Questions')
@section('PageContent')

    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Case Study Questions</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                        class="fadeIn animated bx bx-file"></i>New Case Study
                </button>
            </div>
        </div>
        <hr/>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Question</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($questions as $question)
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;"><div style="white-space: break-spaces;">{!! $question->question !!}</div>  </td>
                                        <td style="vertical-align: middle;">
                                         <!--Edit and Delete-->
                                            <a href="{{ route('case-study-question.edit', $question->id) }}" class="btn btn-primary btn-sm"><i
                                                    class="fadeIn animated bx bx-edit-alt"></i></a>
                                            <button data-id="{{ $question->id }}" class="btn btn-danger btn-sm delete"><i
                                                    class="fadeIn animated bx bx-trash"></i></button>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('case-study-question.store') }}" method="POST" id="addQuestionForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info bg-gradient">
                        <h5 class="modal-title text-white">Add Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="question">Question :<i class="text-danger">*</i> </label>
                                    <textarea name="question" id="question" class="form-control" rows="10"></textarea>
                                    <span class="text-danger error-text question_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('question', {
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
        });

        $('#addQuestionForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#add_question_modal').modal('hide');
                        toastr.success(data.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                }
            });


        });
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            var url = '{{ route('case-study-question.destroy', ':id') }}';
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
            }).then(function(result) {
                if (result.value) {
                    //ajax call delete method
                    $.ajax({
                        type: "DELETE",
                        url: url.replace(':id', id),
                        success: function(data) {
                            if (data.status == 200) {
                                toastr.success(data.message);
                                setTimeout(function() {
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
