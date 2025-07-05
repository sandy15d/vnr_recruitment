@extends('layouts.master')
@section('title', 'Question Bank')
@section('PageContent')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Question Update</div>

        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('question_bank.update', $question_detail->id) }}" method="POST" id="addQuestionForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="subject_id">Subject :<span class="text-danger">*</span></label>
                                            <select name="subject_id" id="subject_id" class="form-control form-select">
                                                <option value="">Select</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ $subject->id == $question_detail->subject_id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text subject_id_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="suitable_for">Suitable for :<span class="text-danger">*</span></label>
                                            <select name="suitable_for" id="suitable_for" class="form-control form-select">
                                                <option value="">Select</option>
                                                @php
                                                    $suitable_array = ['Fresher', 'Intermediate', 'Experienced', 'All'];
                                                    foreach ($suitable_array as $key => $value) {
                                                        if ($value == $question_detail->suitable_for) {
                                                            echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                                        } else {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                    }
                                                @endphp
                                            </select>
                                            <span class="text-danger error-text suitable_for_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="level">Difficulty Level :<span class="text-danger">*</span></label>
                                            <select name="level" id="level" class="form-control form-select">
                                                <option value="">Select</option>
                                                @php
                                                    $level_array = ['Easy', 'Moderate', 'Hard'];
                                                    foreach ($level_array as $key => $value) {
                                                        if ($value == $question_detail->level) {
                                                            echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                                        } else {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                    }
                                                @endphp
                                            </select>
                                            <span class="text-danger error-text level_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="question_type">Question Type :<span class="text-danger">*</span></label>
                                            <select name="question_type" id="question_type" class="form-control form-select">
                                                <option value="">Select</option>
                                                @php
                                                    $type_array = ['MCQ', 'True/False', 'Descriptive'];
                                                    foreach ($type_array as $key => $value) {
                                                        if ($value == $question_detail->question_type) {
                                                            echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                                        } else {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                    }
                                                @endphp
                                            </select>
                                            <span class="text-danger error-text question_type_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="question">Question :<span class="text-danger">*</span></label>
                                            <textarea name="question" id="question" class="form-control" rows="2">{{$question_detail->question}}</textarea>
                                            <span class="text-danger error-text question_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="mcq_opt" class="{{$question_detail->question_type == 'MCQ' ? '' : 'd-none'}}">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="option_a">Option A :</label>
                                                <textarea name="option_a" id="option_a" class="form-control" rows="2">{{$question_detail->option_a}}</textarea>
                                                <span class="text-danger error-text option_a_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="option_b">Option B :</label>
                                                <textarea name="option_b" id="option_b" class="form-control" rows="2">{{$question_detail->option_b}}</textarea>
                                                <span class="text-danger error-text option_b_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="option_c">Option C :</label>
                                                <textarea name="option_c" id="option_c" class="form-control" rows="2">{{$question_detail->option_c}}</textarea>
                                                <span class="text-danger error-text option_c_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="option_d">Option D :</label>
                                                <textarea name="option_d" id="option_d" class="form-control" rows="2">{{$question_detail->option_d}}</textarea>
                                                <span class="text-danger error-text option_d_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="mcq_ans" class="{{$question_detail->question_type == 'MCQ' ? '' : 'd-none'}}">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="answer">Answer: <span class="text-danger">*</span></label>
                                                <select name="answer" id="answer" class="form-control form-select">
                                                    <option value="">Select</option>

                                                    @php
                                                        $answer_array = ['option_a'=>'A', 'option_b'=>'B', 'option_c'=>'C', 'option_d'=>'D'];
                                                        foreach ($answer_array as $key => $value) {
                                                            if ($key == $question_detail->correct_option) {
                                                                echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                                            } else {
                                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                                            }
                                                        }
                                                    @endphp
                                                </select>
                                                <span class="text-danger error-text answer_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="true_ans" class="{{$question_detail->question_type == 'True/False' ? '' : 'd-none'}}">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="true_false_answer">Answer: <span class="text-danger">*</span></label>
                                                <select name="true_false_answer" id="true_false_answer"
                                                        class="form-control form-select">
                                                    <option value="">Select</option>
                                                    <option value="True" @if($question_detail->correct_option == 'True') selected @endif>True</option>
                                                    <option value="False" @if($question_detail->correct_option == 'False') selected @endif>False</option>
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

                        toastr.success(data.message);
                        setTimeout(function() {

                          window.close();

                        }, 1000);
                    }
                }
            });


        });

    </script>
@endsection
