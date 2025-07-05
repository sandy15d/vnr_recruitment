@extends('layouts.master')
@section('title', 'Question Bank')
@section('PageContent')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Question Bank</div>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm" id="add_question" data-bs-toggle="modal"
                    data-bs-target="#add_question_modal"><i class="fadeIn animated bx bx-plus"></i>Question</button>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed" id="que_bank_table" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                    <tr>
                                        <td class="td-sm">S.No.</td>
                                        <td>Subject</td>
                                        <td>Department</td>
                                        <td>Total Que.</td>
                                        <td>Status</td>
                                        <td>View</td>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($QuestionBank as $item)
                                        <tr>
                                            <td class="td-sm text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->subject_name }}</td>
                                            <td class="text-center">{{ $item->department }}</td>
                                            <td class="text-center">{{ $item->total_question }}</td>
                                            <td class="text-center">
                                                @if ($item->status == 'A')
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>
                                            <td class="text-center"> <a href="{{ url('question_bank', $item->id) }}"
                                                    target="_blank" class="btn  btn-xs"><i class="bx bx-show"></i></a> </td>
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

    <div class="modal fade" id="add_question_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('question_bank.store') }}" method="POST" id="addQuestionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="subject_id">Subject :<font class="text-danger">*</font></label>
                                    <select name="subject_id" id="subject_id" class="form-control form-select">
                                        <option value="">Select</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text subject_id_error"></span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="suitable_for">Suitable for :<font class="text-danger">*</font></label>
                                    <select name="suitable_for" id="suitable_for" class="form-control form-select">
                                        <option value="">Select</option>
                                        <option value="Fresher">Fresher</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Experienced">Experienced</option>
                                        <option value="All">All</option>
                                    </select>
                                    <span class="text-danger error-text suitable_for_error"></span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="level">Difficulty Level :<font class="text-danger">*</font></label>
                                    <select name="level" id="level" class="form-control form-select">
                                        <option value="">Select</option>
                                        <option value="Easy">Easy</option>
                                        <option value="Moderate">Moderate</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                    <span class="text-danger error-text level_error"></span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="question_type">Question Type :<font class="text-danger">*</font></label>
                                    <select name="question_type" id="question_type" class="form-control form-select">
                                        <option value="">Select</option>
                                        <option value="MCQ">MCQ</option>
                                        <option value="True/False">True/False</option>
                                        <option value="Descriptive">Descriptive</option>
                                    </select>
                                    <span class="text-danger error-text question_type_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="question">Question :<font class="text-danger">*</font></label>
                                    <textarea name="question" id="question" class="form-control" rows="2"></textarea>
                                    <span class="text-danger error-text question_error"></span>
                                </div>
                            </div>
                        </div>
                        <div id="mcq_opt" class="d-none">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="option_a">Option A :</label>
                                        <textarea name="option_a" id="option_a" class="form-control" rows="2"></textarea>
                                        <span class="text-danger error-text option_a_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="option_b">Option B :</label>
                                        <textarea name="option_b" id="option_b" class="form-control" rows="2"></textarea>
                                        <span class="text-danger error-text option_b_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="option_c">Option C :</label>
                                        <textarea name="option_c" id="option_c" class="form-control" rows="2"></textarea>
                                        <span class="text-danger error-text option_c_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="option_d">Option D :</label>
                                        <textarea name="option_d" id="option_d" class="form-control" rows="2"></textarea>
                                        <span class="text-danger error-text option_d_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="mcq_ans" class="d-none">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="answer">Answer: <font class="text-danger">*</font></label>
                                        <select name="answer" id="answer" class="form-control form-select">
                                            <option value="">Select</option>
                                            <option value="option_a">A</option>
                                            <option value="option_b">B</option>
                                            <option value="option_c">C</option>
                                            <option value="option_d">D</option>
                                        </select>
                                        <span class="text-danger error-text answer_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="true_ans" class="d-none">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="true_false_answer">Answer: <font class="text-danger">*</font></label>
                                        <select name="true_false_answer" id="true_false_answer"
                                            class="form-control form-select">
                                            <option value="">Select</option>
                                            <option value="True">True</option>
                                            <option value="False">False</option>
                                        </select>
                                        <span class="text-danger error-text true_false_answer_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $(document).ready(function() {

            $('#que_bank_table').DataTable();
            CKEDITOR.replace('question', {
                height: '100px',
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
            CKEDITOR.replace('option_a', {
                height: '80px',
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
            CKEDITOR.replace('option_b', {
                height: '80px',
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
            CKEDITOR.replace('option_c', {
                height: '80px',
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
            CKEDITOR.replace('option_d', {
                height: '80px',
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
            });
        });

        $(document).on('change', '#question_type', function() {
            var question_type = $(this).val();
            if (question_type == 'MCQ') {
                $('#mcq_opt').removeClass('d-none');
                $('#mcq_ans').removeClass('d-none');
                $('#true_ans').addClass('d-none');
            } else if (question_type == 'True/False') {
                $('#true_ans').removeClass('d-none');
                $('#mcq_opt').addClass('d-none');
                $('#mcq_ans').addClass('d-none');
            } else {
                $('#mcq_opt').addClass('d-none');
                $('#true_ans').addClass('d-none');
                $('#mcq_ans').addClass('d-none');
            }
        });

        $('#addQuestionForm').on('submit', function(e) {
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
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#add_question_modal').modal('hide');
                        toastr.success(data.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                }
            });


        });

    </script>
@endsection
