@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Quest Failed Requests</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Failed Requests </h2>
                    </div>
                    <div class="body table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            <thead>
                                <th>Placer Order ID</th>
                                <th>Filler Order ID</th>
                                <th>Quest Request ID</th>
                                <th>Control ID</th>
                                <!-- <th>Patient</th> -->
                                <th>Recieved At</th>
                                <th>Error Message</th>
                                <!-- <th>Details</th> -->
                                <th>
                                    <center>Action</center>
                                </th>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                <tr>
                                    <td>{{$result->placer_order_num}}</td>
                                    <td>{{$result->filler_order_num}}</td>
                                    <td>{{$result->get_quest_request_id}}</td>
                                    <td>{{$result->control_id}}</td>
                                    <td>{{date('Y-m-d',strtotime($result->created_at))}}</td>
                                    <td>{{ucfirst($result->status)}}</td>
                                    <td>
                                        <center>

                                            <a href="{{route('quest_failed_request_details',$result->id)}}"
                                                class="btn p-2 btn-primary my-0 btn-raised btn-circle waves-effect waves-circle waves-float">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </center>
                                    </td>
                                    <!-- <td>
                                        <center>
                                            <a href="{{route('resolve_request',$result->id)}}" 
                                                class="btn p-2 btn-primary my-0 btn-raised btn-circle waves-effect waves-circle waves-float">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            
                                        </center>
                                    </td> -->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <center>No Delete Requests</center>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection