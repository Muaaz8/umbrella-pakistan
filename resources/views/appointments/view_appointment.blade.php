@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2> Umbrella Health Care Systems</h2>
            <small class="text-muted"></small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View Appointment Details </h2>
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_10" role="tablist" aria-multiselectable="true">
                              
                                <div class="panel">
                                    <div class="panel-heading bg-blue" role="tab" id="headingTwo_10">
                                        <h4 class="panel-title"> <a class="collapsed" role="button"
                                                data-toggle="collapse" data-parent="#accordion_10"
                                                href="#session_{{$sessions->id}}" aria-expanded="true"
                                                aria-controls="#session_{{$sessions->id}}" style="font-weight:bold">
                                                @if($user_type=='patient')
                                                Dr. {{ucwords($sessions->doc_name)}}
                                                @elseif($user_type=='doctor')
                                                {{ucwords($sessions->pat_name)}}
                                                @elseif($user_type=='admin')
                                                Dr. {{ucwords($sessions->doc_name).' with '.ucwords($sessions->pat_name)}}
                                                @endif
                                            <span class="float-right">{{$sessions->date}}</span> </a> </h4>
                                    </div>
                                    <div id="#session_{{$sessions->id}}" class="panel-collapse" role="tabpanel"
                                        aria-labelledby="headingTwo_10">
                                        <div class="panel-body">                                           
                                            <table class="col-12">
                                                <tr class="row">
                                                    <!-- <div class="col-md-12"> -->
                                                    <td class="col-md-1"><b>Diagnosis: </b></td>
                                                    <td colspan="3" class="pl-3">{{ucfirst($sessions->diagnosis)}}</td>
                                                    <!-- </div> -->
                                                </tr>
                                                @if($user_type =='doctor')
                                                <input id="notes_text" hidden="" value="{{ucfirst($sessions->provider_notes)}}">

                                                <tr class="row">
                                                    <!-- <div class="col-md-12"> -->
                                                    <td class="col-md-1"><b>Notes: </b></td>
                                                    <td colspan="3" class="pl-3" id="notes"></td>
                                                    <!-- </div> -->
                                                </tr>
                                                @endif
                                                <tr class="row">
                                                    <!-- <div class="col-md-12"> -->
                                                    @if($user_type=='patient')
                                                    <td class="col-md-1"><b>Cost: </b></td>
                                                    <td class="col-md-3">$30</td>
                                                    @elseif($user_type=='doctor')
                                                    <td class="col-md-1"><b>Earning: </b></td>
                                                    <td class="col-md-3">$24</td>
                                                    @elseif($user_type=='admin')
                                                    <td class="col-md-1"><b>Payment: </b></td>
                                                    <td class="col-md-3">$30</td>
                                                    @endif
                                                    <td class="col-md-2"><b>Start Time: </b></td>
                                                    <td class="col-md-3">{{$sessions->start_time}}</td>

                                                    <!-- </div> -->
                                                    <!-- <div class="col-md-12"> -->
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-1"><b>Date: </b></td>
                                                    <td class="col-md-3">{{$sessions->date}}</td>
                                                    <td class="col-md-2"><b>End Time: </b></td>
                                                    <td class="col-md-3">{{$sessions->end_time}}</td>
                                                    <!-- </div> -->
                                                    <!-- <div class="col-md-12 row clearfix"> -->
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-1"><b>Recording: </b></td>
                                                    <td class="col-md-3"><a href="https://www.umbrellamd.cf/video_chat/server/recordings/{{$sessions->recording}}" target="_blank">{{$sessions->recording}}</a></td>
                                                </tr>
                                            </table>
                                            <!-- </div> -->
                                            <!-- </div>  -->
                                            <div class="body table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Recommendation</th>
                                                            <th>Dosage</th>
                                                            <th>Comment</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($sessions->pres as $pres)
                                                        @if($pres->prod_detail!=null)
                                                        @if($pres->prod_detail->mode=='medicine')
                                                        <tr class="medicine-bg">
                                                            @elseif($pres->prod_detail->mode=='lab-test')
                                                        <tr class="lab-bg">
                                                            @elseif($pres->prod_detail->mode=='imaging')
                                                        <tr class="imaging-bg">
                                                            @endif
                                                            <td>{{ucfirst($pres->prod_detail->name)}}</td>
                                                            <td>{{ucfirst($pres->usage)}}</td>
                                                            <td>{{ucfirst($pres->comment)}}</td>
                                                            <td>{{ucfirst($pres->type)}}</td>
                                                            <!-- Status from Cart table -->
                                                            <td>{{ucfirst($pres->cart_status)}}</td>
                                                        </tr>
                                                        @endif
                                                        @empty
                                                        <tr>
                                                            <td colspan="5"><center>No Recommendations</center></td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                                              
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<!-- <script src="asset_admin/js/pages/tables/jquery-datatable.js"></script> -->
<script>
$(document).ready(function(){
    $('#notes').append($('#notes_text').val());
})
</script>
@endsection