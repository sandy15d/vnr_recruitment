@extends('layouts.master')
@section('title', 'MRF Allocated')
@section('PageContent')
    <style>
        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb align-items-center mb-3">




        </div>
        <!--end breadcrumb-->
        <hr/>
        <div class="card">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-sm" id="shared_to_me" data-status='Open'>Shared to Me
                        </button>
                        <button class="btn btn-outline-primary btn-sm pull-right" data-status='Close' id="shared_by_me">
                            Shared By Me </button>
                    </div>


                </div>
                <hr/>
                <div>
                    <table
                        class="table  table-hover table-striped table-condensed align-middle text-center table-bordered"
                        id="MRFTable" style="width: 100%">
                        <thead class="text-center bg-success text-light">
                        <tr class="text-center">
                            <td></td>
                            <td class="th-sm">S.No</td>
                            <td>Reference No</td>
                            <td>Name</td>
                            <td>Phone</td>
                            <th>Email</th>
                            <td>Experience</td>
                            <td>Cur. Company</td>
                            <td>Designation</td>
                            <td>Location</td>
                            <td>Education</td>
                            <td>Profile Shared By/To</td>
                            <td>Shared Date</td>
                            <td>Action</td>
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
