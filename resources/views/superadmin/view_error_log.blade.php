@extends('layouts.admin')

@section('content')
{{-- {{ dd($data) }} --}}
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Error Log</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <th>User Id</th>
                                <th>User Name</th>
                                <th>Error Code</th>
                                <th>Error Text</th>
                                {{-- <th>
                                    <center>Action</center>
                                </th> --}}
                            </thead>
                            <tbody>
                                @forelse($data as $dt)
                                <tr>
                                    {{-- <td>{{\App\User::getName($dt->id)}} <span class="label-info label">new</span></td> --}}
                                    <td>{{ $dt->id }}</td>
                                    <td>{{$dt->name." ".$dt->last_name}}</td>
                                    <td>{{$dt->Error_code}}</td>
                                    <td>{{$dt->Error_text}}</td>
                                    {{-- <td>
                                        <center>
                                            <a href="{{ route('pending_doctor_detail',$doc->id)}}">
                                                <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                        </center>

                                    </td> --}}

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <center>No Error Code</center>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
@endsection
