@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>View All Psychiatrist Services</h2>
            <small class="text-muted">Welcome to Umbrelamd Health Care</small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>ID</th>
                        <th>Psychiatrist Service</th>
                        <th>Description</th>

                        <th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($data as $service)

                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->title }}</td>
                            <td>{{ $service->description }}</td>
                           <td style="padding:0px">
                                {{-- <center>
                                    <a href="{{ route('destroySpec',['id'=>$spec->id]) }}">
                                    <button class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-trash"></i></button>
                                    </a>
                                </center> --}}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
