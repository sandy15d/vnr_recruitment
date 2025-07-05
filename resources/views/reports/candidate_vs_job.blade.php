
@extends('layouts.master')
@section('title', 'FIRO B Report')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Candidates VS JobPost
                </div>
            </div>
            <!--end breadcrumb-->
            <hr>
        </div>
        <div class="card border-top border-0 border-4 border-primary mb-1">
            <div class="card-body">
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <thead class="text-center">
                        <td>S.No</td>
                        <td>JobPost</td>
                        <td>Created By</td>
                        <td>Total Applied</td>
                        <td>HR Screening</td>
                        <td>Tech Screening</td>
                        <td>Interview</td>
                        <td>Offer Generate</td>
                        <td>Offer Accept</td>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($jobpost_list as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td>{{ $item->Title }} <br>{{ $item->JobCode }}</td>
                                <td>{{ getFullName($item->CreatedBy) }}</td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
