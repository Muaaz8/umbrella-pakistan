@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Quest Failed Request</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <!-- <h2>All Failed Requests </h2> -->
                    </div>
                    <div class="body">
                        <!-- fields -->
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                Request ID
                            </div>
                            <div class="col-md-9">
                                {{$result->get_quest_request_id}}
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                Control ID
                            </div>
                            <div class="col-md-9">
                                {{$result->control_id}}
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                Patient Matching
                            </div>
                            <div class="col-md-9">
                                Status:: {{$result->patient_matching['status']}}<br>
                                Attributes:: {{$result->patient_matching['attributes']}}<br>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                Provider Matching
                            </div>
                            <div class="col-md-9">
                                Status:: {{$result->provider_matching['status']}}<br>
                                Name:: {{$result->provider_matching['name']}}<br>
                                NPI:: {{$result->provider_matching['NPI']}}<br>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-3">
                                Order Matching
                            </div>
                            <div class="col-md-9">
                                Status:: {{$result->order_matching['status']}}<br>
                                Placer Order Number:: {{$result->order_matching['placer_order_num']}}<br>
                            </div>
                        </div>
                        <div class="row col-md-12">
                        <div class="col-md-3">
                                Action
                            </div>
                            <div class="col-md-9">
                                <a type="button" href="{{url('uploads/lab_reports/'.$result->file)}}" target="_blank" class="btn btn-primary">View </a>
                                <a type="button" href="{{route('resolve_request',$result->id)}}"  class="btn btn-success">Resolved </a>
                                
                        </div>

                    </div>
                </div>
            </div>
        </div>
</section>
@endsection