@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted"></small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Session with {{$session['doc_name']}}<small>All related details are listed here</small>
                        </h2>
                    </div>
                    <div class="col-12">
                        {{--<div class="panel-heading" role="tab" id="headingTwo_10">
                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                    data-parent="#accordion_10" href="#session_{{$session->id}}" aria-expanded="false"
                        aria-controls="session_{{$session->id}}" style="font-weight:bold">
                        @if($user_type=='patient')
                        {{ucwords($session->doc_name)}}
                        @elseif($user_type=='doctor')
                        {{ucwords($session->pat_name)}}
                        @endif
                        <span class="float-right">{{$session->date}}</span> </a> </h4>
                    </div>--}}
                    <!-- <div id="session_{{$session->id}}" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="headingTwo_10">
                            <div class="panel-body"> -->
                    <table class="col-12 ml-4">
                        <tr class="row">
                            <!-- <div class="col-md-12"> -->
                            <td class="col-md-1"><b>Diagnosis: </b></td>
                            <td colspan="3" class="pl-3">{{ucfirst($session->diagnosis)}}</td>
                            <!-- </div> -->
                        </tr>
                        @if($user_type=='doctor')
                        <input id="notes_text" hidden="" value="{{ucfirst($session->provider_notes)}}">

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
                            @endif
                            <td class="col-md-2"><b>Start Time: </b></td>
                            <td class="col-md-3">{{$session->start_time}}</td>

                            <!-- </div> -->
                            <!-- <div class="col-md-12"> -->
                        </tr>
                        <tr class="row">
                            <td class="col-md-1"><b>Date: </b></td>
                            <td class="col-md-3">{{$session->date}}</td>
                            <td class="col-md-2"><b>End Time: </b></td>
                            <td class="col-md-3">{{$session->end_time}}</td>
                            <!-- </div> -->
                            <!-- <div class="col-md-12 row clearfix"> -->
                        </tr>
                    </table>
                    <!-- </div> -->
                    <!-- </div>  -->
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="lab-bg">
                                    <th>Recommended Medication</th>
                                    <th>Medication Category</th>
                                    <th>Dosage</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($session->pres as $pres)
                                @if($pres->prod_detail!=null)
                                @if($pres->prod_detail->mode=='medicine')
                                <tr>
                                    <td>{{ucfirst($pres->prod_detail->name)}}</td>
                                    <td>{{ucfirst($pres->type)}}</td>
                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->usage != "") ? $pres->usage : $t !!}</td>
                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->comment != "") ? $pres->comment : $t !!}</td>

                                    <td>{{ucfirst($pres->cart_status)}}</td>
                                </tr>
                                @elseif($pres->prod_detail->mode=='lab-test')
                                <tr>
                                    <td>{{ucfirst($pres->prod_detail->TEST_NAME)}}</td>
                                    <td>{{ucfirst($pres->type)}}</td>
                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->usage != "") ? $pres->usage : $t !!}</td>

                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->comment != "") ? $pres->comment : $t !!}</td>

                                    <td>{{ucfirst($pres->cart_status)}}</td>
                                </tr>

                                @elseif($pres->prod_detail->mode=='imaging')
                                <tr>
                                    <td>{{ucfirst($pres->prod_detail->name)}}</td>
                                    <td>{{ucfirst($pres->type)}}</td>
                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->usage != "") ? $pres->usage : $t !!}</td>

                                    @php
                                    $t ="<div class='text-center'>N/A</div>";
                                    @endphp
                                    <td>{!! ($pres->comment != "") ? $pres->comment : $t !!}</td>
                                    <!-- Status from Cart table -->
                                    <td>{{ucfirst($pres->cart_status)}}</td>
                                </tr>
                                @endif

                                @endif
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <center>
                                            No Recommendations</center>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- </div>
                            </div> -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</section>
@endsection