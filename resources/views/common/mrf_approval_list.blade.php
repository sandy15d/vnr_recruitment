@extends('layouts.master')
@section('title', 'Pending MRF Approval List')
@section('PageContent')
    <div class="page-content">
        <style>
            .ribbon-2 {
                --f: 10px; /* control the folded part*/
                --r: 15px; /* control the ribbon shape */
                --t: 10px; /* the top offset */
                position: absolute;
                inset: var(--t) calc(-1 * var(--f)) auto auto;
                padding: 0 10px var(--f) calc(10px + var(--r));
                clip-path: polygon(0 0, 100% 0, 100% calc(100% - var(--f)), calc(100% - var(--f)) 100%,
                calc(100% - var(--f)) calc(100% - var(--f)), 0 calc(100% - var(--f)),
                var(--r) calc(50% - var(--f) / 2));
                background: #BD1550;
                box-shadow: 0 calc(-1 * var(--f)) 0 inset #0005;
            }
        </style>
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">MRF Approval List</div>
        </div>
        <div class="row">
            @foreach($mrf_list as $mrf)
                <div class="col-md-12">
                    <div class="card border-success border-bottom border-3 border-0">
                        <div class="ribbon-2 text-light bg-primary">
                            @php
                                $types = [
                                            'N' => 'New',
                                            'N_HrManual' => 'New by HR',
                                            'SIP' => 'SIP/Internship',
                                            'SIP_HrManual' => 'SIP/Internship by HR',
                                            'Campus' => 'Campus',
                                            'Campus_HrManual' => 'Campus by HR',
                                            'R' => 'Replacement',
                                            'R_HrManual' => 'Replacement by HR',
                                                                              ];
                            @endphp
                            {{ $types[$mrf->Type] }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-success">{{$mrf->JobCode}}</h5>
                            <h6 class="text-success mb-2">MRF Created By: &nbsp;
                                @if($mrf->Type == 'N_HrManual' || $mrf->Type == 'SIP_HrManual' || $mrf->Type == 'Campus_HrManual' || $mrf->Type == 'R_HrManual')
                                    Hr on Behalf of {{getFullName($mrf->OnBehalf)}}
                                @else
                                    {{getFullName($mrf->CreatedBy)}}
                                @endif
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 40%"><p class="card-text"><b>Reason for Creating <span
                                                        style="float: right">:</span></b></p></td>
                                    <td>{{$mrf->Reason}}</td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Department <span style="float: right">:</span></b></p>
                                    </td>
                                    <td>{{getDepartment($mrf->DepartmentId)}}</td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Post Title <span style="float: right">:</span></b></p>
                                    </td>
                                    <td>{{getDesignation($mrf->DesigId)}}</td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Location & Manpower <span style="float: right">:</span></b>
                                        </p></td>
                                    <td>
                                        @php
                                            //$data = unserialize($mrf->LocationIds, ['allowed_classes' => false]);
                                        $data = \Illuminate\Support\Facades\DB::table('mrf_location_position')->where('MRFId',$mrf->MRFId)->get();
                                        @endphp

                                        @if (!empty($data) && is_array($data))
                                            <ul>
                                                @foreach ($data as $item)
                                                    <li>
                                                        {{ getDistrictName($item['City']) }}
                                                        , {{ getStateCode($item['State']) }} - {{ $item['Nop'] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>No data available.</p>
                                        @endif
                                    </td>
                                </tr>
                                @if($mrf->Type =='SIP' || $mrf->Type =='SIP_HrManual')
                                    <tr>
                                        <td>
                                            <p class="card-text"><b>Desired Stipend (in Rs. Per Month) <span
                                                            style="float: right">:</span></b></p>
                                        </td>
                                        <td>
                                            {{$mrf->Stipend}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="card-text"><b>Other Benefits <span
                                                            style="float: right">:</span></b></p>
                                        </td>
                                        <td>
                                            @if($mrf->TwoWheeler !== null)
                                                Two Wheeler reimbursement of Rs. {{$mrf->TwoWheeler}} per month.
                                            @endif
                                            @if($mrf->DA !== null)
                                                DA of Rs. {{$mrf->DA}} per day.
                                            @endif

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p class="card-text"><b>Training Duration <span
                                                            style="float: right">:</span></b></p>
                                        </td>
                                        <td>
                                            {{date('d-m-Y',strtotime($mrf->Tr_Frm_Date))}}
                                            To {{date('d-m-Y',strtotime($mrf->Tr_To_Date))}}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><p class="card-text"><b>Desired CTC <span style="float: right">:</span></b>
                                            </p>
                                        </td>
                                        <td>{{$mrf->MinCTC}} - {{$mrf->MaxCTC}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><p class="card-text"><b>Desired Education <span
                                                        style="float: right">:</span></b></p></td>
                                    <td>
                                        @if($mrf->EducationId !== null)
                                            <ul>
                                                @foreach (unserialize($mrf->EducationId, ['allowed_classes' => false]) as $item)
                                                    <li>
                                                        {{ getEducationCodeById($item['e']) }}
                                                        - {{ getSpecializationbyId($item['s']) }}

                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Desired University / Collage <span style="float: right">:</span></b>
                                        </p></td>
                                    <td>
                                        {{--@if($mrf->EducationInsId !== null)
                                            @php
                                                $university  = unserialize($mrf->EducationInsId, ['allowed_classes' => false]);

                                                    echo getCollegeById($university);

                                            @endphp

                                        @endif--}}

                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Desired Work Experience <span
                                                        style="float: right">:</span></b></p></td>
                                    <td>{{$mrf->WorkExp}}</td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Job Description <span style="float: right">:</span></b>
                                        </p></td>
                                    <td>{!! $mrf->Info !!}</td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Key Skills <span style="float: right">:</span></b></p>
                                    </td>
                                    <td>
                                        @if($mrf->KeyPositionCriteria !== null)
                                            @php
                                                $skills =   unserialize($mrf->KeyPositionCriteria, ['allowed_classes' => false]);
                                            @endphp
                                            @foreach($skills as $key => $value)
                                                <span class="badge badge-purple">{{$value}}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="card-text"><b>Remarks <span style="float: right">:</span></b></p></td>
                                    <td>{{$mrf->Remarks}}</td>
                                </tr>
                                @if($mrf->reporting_approve == 'R' || $mrf->hod_approve == 'R')
                                    <tr>
                                        <td colspan="2" class="text-danger font-14">MRF has been rejected by {{getFullName($reporting_id)}} on {{date('d-m-Y')}} with remarks: {{$mrf->reporting_remark}}</td>
                                    </tr>
                                @endif
                            </table>


                            <hr>
                            @php

                                $reporting_id = $mrf->reporting_id;
                                $hod_id = $mrf->hod_id;
                                $management_id = $mrf->management_id;
                                $reporting_approve = $mrf->reporting_approve;
                                $hod_approve = $mrf->hod_approve;
                                $management_approve = $mrf->management_approve;

                                $button_status = '';
                                if($reporting_id === Auth::user()->id){
                                    $button_status = 'show';
                                }
                                if($hod_id === Auth::user()->id && $reporting_approve != 'N'){
                                    $button_status = 'show';
                                }
                                if($management_id === Auth::user()->id && $hod_approve != 'N' ){
                                    $button_status = 'show';
                                }
                            @endphp
                            <div class="justify-content-right mt-2 float-end {{$button_status ==='show' ? 'd-block' : 'd-none'}}">
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger"
                                   onclick="rejectMrf({{$mrf->MRFId}})"><i class="fa fa-ban"></i>Reject
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-primary"
                                   onclick="approveMrf({{$mrf->MRFId}})"><i class="fa fa-check"></i>Approve
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        function rejectMrf(MRFId) {
            Swal.fire({
                title: 'Reject MRF',
                text: "Please enter remarks",
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Enter remarks',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                preConfirm: (remarks) => {
                    if (!remarks) {
                        Swal.showValidationMessage('Remarks are required.');
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('rejectMRF') }}",
                            data: {
                                MRFId: MRFId,
                                remarks: remarks
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $("#loader").modal('show');
                            },
                            success: function (data) {
                                if (data.status == 200) {
                                    $("#loader").modal('hide');
                                    Swal.fire('Rejected', 'MRF has been rejected with remarks: ' + remarks, 'error');
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    toastr.error(data.msg);
                                }
                            }

                        })
                    }
                }
            });
        }

        function approveMrf(MRFId) {
            Swal.fire({
                title: 'Approve MRF',
                text: "Please enter remarks",
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Enter remarks',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                preConfirm: (remarks) => {
                    if (!remarks) {
                        Swal.showValidationMessage('Remarks are required.');
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('approveMRF') }}",
                            data: {
                                MRFId: MRFId,
                                remarks: remarks
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                $("#loader").modal('show');
                            },
                            success: function (data) {
                                if (data.status == 200) {
                                    $("#loader").modal('hide');
                                    Swal.fire('Approved', 'MRF has been approved with remarks: ' + remarks, 'success');
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    toastr.error(data.msg);
                                }
                            }

                        })
                    }
                }
            });
        }

    </script>
@endsection
