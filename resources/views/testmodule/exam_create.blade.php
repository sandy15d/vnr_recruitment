@extends('layouts.master')
@section('title', 'Create Exam')
@section('PageContent')
    <div class="page-content">
        <div class="col-xl-8 mx-auto">
            <div class="card border-top border-0 border-4 border-success">
                <div class="card-body p-3">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-success"></i>
                        </div>
                        <h5 class="mb-0 text-success">Create Online Exam </h5>
                    </div>
                    <hr class="mb-3">
                    <form action="{{route('exam_master.store')}}" method="POST" id="addExamForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-borderless">
                                    <tbody>
                                    <tr>
                                        <th style="width: 20%">Exam Name <span class="text-danger">*</span></th>
                                        <td>
                                            <input type="text" name="exam_name" id="exam_name" class="form-control">
                                            <span class="text-danger error-text exam_name_error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Test Paper <span class="text-danger">*</span></th>
                                        <td>
                                            @foreach($test_papers as $key => $test_paper)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" style="font-size: 16px;"
                                                           type="checkbox" id="test_paper_{{$test_paper['id']}}"
                                                           name="test_paper[]" value="{{$test_paper['id']}}">
                                                    <label class="form-check-label"
                                                           for="test_paper_{{$test_paper['id']}}"
                                                           style="cursor: pointer;">{{$test_paper['subject_name']}}</label>
                                                </div>
                                            @endforeach
                                                <span class="text-danger error-text test_paper_error"></span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Time for each Paper</th>
                                        <td>
                                            <input type="number" name="time" id="time" class="form-control d-inline"
                                                   style="width: 30%"> <span>In minutes (Except FIRO B)</span>
                                            <span class="text-danger error-text time_error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Time Reminder to Candidate</th>
                                        <td>
                                            <input type="number" name="time_reminder" id="time_reminder"
                                                   class="form-control d-inline" style="width: 30%" min="2"> <span>In minutes</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Max. number of alerts for switching exam window</th>
                                        <td>
                                            <select name="max_alert" id="max_alert" class="form-select"
                                                    style="width: 30%">
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>

                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Instruction <span class="text-danger">*</span></th>
                                        <td>
                                            <textarea name="instruction" id="instruction" class="form-control"></textarea>
                                            <span class="text-danger error-text instruction_error"></span>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="saveExam">Submit</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('instruction', {});
            $('#addExamForm').on('submit', function (e) {
                e.preventDefault();
                let form = this;
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
                        if (data.status === 400) {
                            $.each(data.error, function (prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $(form)[0].reset();
                            toastr.success(data.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                });


            });
        });
    </script>
@endsection
