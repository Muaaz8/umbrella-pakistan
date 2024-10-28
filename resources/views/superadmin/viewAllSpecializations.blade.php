@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>View All Specialization</h2>
            <small class="text-muted">Welcome to Umbrelamd Health Care</small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Specialization</th>
                        <th>Specialization Status</th>

                        <th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($data as $spec)

                        <tr>
                            <td>{{ $spec->name }}</td>
                            <td>
                                @if ($spec->status==0)
                                    Deactive
                                @else
                                    Active
                                @endif

                            </td>
                           <td style="padding:0px">
                                <center>
                                    <a href="{{ route('editSpec',['id'=>$spec->id]) }}">
                                    <button class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-edit"></i></button>
                                    </a>
                                    <a href="{{ route('destroySpec',['id'=>$spec->id]) }}">
                                    <button class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-trash"></i></button>
                                    </a>
                                </center>
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
