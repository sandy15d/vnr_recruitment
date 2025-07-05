@extends('layouts.master')
@section('title', 'Manual Entry Report')
@section('PageContent')
    <div class="page-content">
        <div class="row"></div>
        <div class="card border-top border-0 border-4 border-success mb-3 ">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="userTable">
                        <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <th>#</th>
                            <th>DEO Name</th>
                            <th>No of Entry</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user_list as $user)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$user->user}}</td>
                                <td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-warning">{{$user->total}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        @endsection
        @section('script_section')
            <script>
                $(document).ready(function () {
                    $('#userTable').DataTable({
                        searching:false,
                        dom: 'Bfrtip',
                    });
                });


            </script>
@endsection
