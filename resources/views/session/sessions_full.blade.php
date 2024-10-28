@extends('layouts.admin')

@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

                    <div class="row p-3 ">
                        <div class="col-xs-6 ol-sm-6 col-md-6 col-lg-6">
                            <h4> All Sessions </h4>
                        </div>
                        <div class="col-xs-6 ol-sm-6 col-md-6 col-lg-6">
                            <input type="text" name="datefilter" id="datePick" class="form-control p-1"
                                placeholder="filter sessoin record by date-range">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group loadFilterData" id="accordion_10" role="tablist"
                                aria-multiselectable="true">
                                @forelse($sessions as $session)
                                <div class="panel">

                                    <div class="panel-heading" role="tab" id="headingTwo_{{$session->id}}">
                                        <h4 class="panel-title">
                                            <a id="session-coll" class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion_10" href="#session_{{$session->id}}"
                                                aria-expanded="false" aria-controls="session_{{$session->id}}"
                                                style="font-weight:bold">
                                                @if($user_type=='patient')
                                                Dr. {{ucwords($session->doc_name)}}
                                                @elseif($user_type=='doctor')
                                                {{ucwords($session->pat_name)}}
                                                @elseif($user_type=='admin')
                                                Dr. {{ucwords($session->doc_name).' with '.ucwords($session->pat_name)}}
                                                @endif
                                                <span class="float-right">{{$session->date}}</span> </a>
                                        </h4>
                                    </div>
                                    <div id="session_{{$session->id}}" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo_{{$session->id}}">
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                {{--  <span class="col-md-3 pl-0"><b>Doctor Name: </b></span>
                                                <span class="col-md-9">
                                                @if($user_type=='patient')
                                                {{ucwords($session->doc_name)}}
                                                @elseif($user_type=='doctor')
                                                {{ucwords($session->pat_name)}}
                                                @endif
                                                </span>--}}

                                            </div>
                                            {{-- <table class="col-12">
                                                <tr class="row">
                                                    <!-- <div class="col-md-12"> -->
                                                    <td class="col-md-1 col-md-4"><b>Session ID: </b></td>
                                                    <td colspan="3" class="pl-3 col-md-4">UEV-{{$session->id}}</td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-1 col-md-4"><b>Diagnosis: </b></td>
                                                <td colspan="3" class="pl-3 col-md-4">{{ucfirst($session->diagnosis)}}
                                                </td>
                                                <!-- </div> -->

                                                <td class="col-md-1 "><b>Recording: </b></td>

                                                <td class="col-md-3 ">
                                                    <a onclick="window.open('{{ route('sessions.record.view',['id'=>$session->id]) }}','popup','width=600,height=500'); return false;"
                                                        target="popup" rola="button"><i class="zmdi zmdi-eye"></i></a>
                                                </td>

                                            </tr>
                                            @if($user_type =='doctor')
                                            <input id="notes_text" hidden=""
                                                value="{{ucfirst($session->provider_notes)}}">

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
                                                <td class="col-md-3">${{ number_format($session->price,2) }}</td>
                                                @elseif($user_type=='doctor')
                                                <td class="col-md-1"><b>Earning: </b></td>
                                                <td class="col-md-3">
                                                    ${{ number_format((80 / 100) * $session->price,2) }}</td>
                                                @elseif($user_type=='admin')
                                                <td class="col-md-1"><b>Payment: </b></td>
                                                <td class="col-md-3">
                                                    ${{ number_format((20 / 100) * $session->price,2) }}</td>
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
                                            </table> --}}

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Session ID</th>
                                                            <th>Diagnosis</th>
                                                            <th>Provider Note</th>
                                                            @if($user_type =='doctor')
                                                            <th>Notes</th>
                                                            <th>Earning</th>
                                                            @endif
                                                            @if($user_type =='admin')
                                                            <th>We Earn</th>
                                                            @endif
                                                            @if($user_type =='patient')
                                                            <th>Cost</th>
                                                            @endif
                                                            @if ($session->refered != null)
                                                            <th>Referred Doctor</th>
                                                            @endif
                                                            <th>Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Recording</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>UEV-{{$session->session_id}}</td>
                                                            <td>{{ucfirst($session->diagnosis)}}</td>
                                                            <td>{{ $session->provider_notes }}</td>
                                                            @if($user_type =='doctor')
                                                            <input id="notes_text" hidden=""
                                                                value="{{ucfirst($session->provider_notes)}}">
                                                            <td id="notes"></td>
                                                            @endif
                                                            <td>${{ number_format($session->price,2) }}</td>
                                                            @if ($session->refered != null)
                                                            <td> {{ $session->refered }} </td>
                                                            @endif
                                                            <td>{{$session->date}}</td>
                                                            <td>{{$session->start_time}}</td>
                                                            <td>{{$session->end_time}}</td>
                                                            <td><a onclick="window.open('{{ route('sessions.record.view',['id'=>$session->id]) }}','popup','width=600,height=500'); return false;"
                                                                    target="popup" rola="button"><i
                                                                        class="zmdi zmdi-videocam"
                                                                        title="click to watch call recording"
                                                                        style="cursor:pointer; font-size:35px;"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr class="lab-bg">
                                                            <th>Recommendation</th>
                                                            @if($user_type =='admin')
                                                            <th>Dosage and Imaging Location</th>
                                                            @else
                                                            <th>Dosage</th>
                                                            @endif
                                                            <th>Comment</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($session->pres as $pres)
                                                        @if($pres->prod_detail!=null)
                                                        @if($pres->prod_detail['mode']=='medicine')
                                                        <tr>
                                                            @elseif($pres->prod_detail['mode']=='lab-test')
                                                        <tr>
                                                            @elseif($pres->prod_detail['mode']=='imaging')
                                                        <tr>
                                                            @endif
                                                            @if(isset($pres->prod_detail['name']))
                                                            <td>{{ucfirst($pres->prod_detail['name'])}}</td>
                                                            @else
                                                            <td>{{ucfirst($pres->prod_detail['DESCRIPTION'])}}</td>
                                                            @endif
                                                            <!-- <td>{{ucfirst($pres->usage)}}</td> -->
                                                            @php
                                                            $t ="<div class='text-center'>N/A</div>";
                                                            @endphp
                                                            <td>{!! ($pres->usage!= "") ? $pres->usage: $t !!}</td>
                                                            @php
                                                            $t ="<div class='text-center'>N/A</div>";
                                                            @endphp
                                                            <td>{!! ($pres->comment!= "") ? $pres->comment : $t !!}</td>

                                                            <!-- <td>{{ucfirst($pres->comment)}}</td> -->
                                                            <td>{{ucfirst($pres->type)}}</td>
                                                            <!-- Status from Cart table -->
                                                            <td>{{ucfirst($pres->cart_status)}}</td>
                                                        </tr>
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
                                            </div>
                                            @if($user_type =='admin')
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr class="lab-bg">
                                                            <th>Sr. #</th>
                                                            <th>Prescription File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($session->pres_files as $pres_file)
                                                        <tr>
                                                            <td>{{$pres_file->id}}</td>
                                                            <td><a href="{{\App\Helper::get_files_url($pres_file->filename)}}" target="_blank">{{$pres_file->filename}}</a></td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="2">
                                                                <center>
                                                                    No Prescription Files</center>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="font-weight-bold col-12 pb-4">
                                    No sessions
                                </div>
                                @endforelse
                            </div>
                            <div class="col-12 link-paginate">
                                {{$sessions->links('pagination::bootstrap-4') }}
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    $('#notes').append($('#notes_text').val());

});
$('#datePick').on('apply.daterangepicker', function(ev, picker) {
    var startDate = picker.startDate;
    var endDate = picker.endDate;
    var from_date = startDate.format('YYYY-MM-DD');
    var to_date = endDate.format('YYYY-MM-DD');
    var url = "{{ url('/filter/session/record/:from_date/to/:to_date') }}";
    url = url.replace(':from_date', from_date);
    url = url.replace(':to_date', to_date);
    $('.loadFilterData').load(url);
});
$(function() {
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });
    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
            'MM/DD/YYYY'));
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});

// $(document).ready(function() {

//     $('.show').css('display', 'none');
// });
// $('.panel').click(function() {
//         // if ($('#session-coll').attr('aria-expanded') == true)

//     if ($('.show').css('display') == 'none'){
//         $('.show').css('display', 'block');
//     }
//     else{
//         $('.show').css('display', 'none');
//     }
// })
// $(function () {

//     if ($('#session-coll').attr('aria-expanded') == true)

//         $('.panel-title').addClass('bg-blue');

//     else
//         $('.panel-title').removeClass('bg-blue');

// })
// $(document).ready(function(){
//   $("a").click(function(){
//     // $("panel-title").addClass("bg-blue");
//     $("panel-title").css("color",'red');

//   });
// });
</script>
@endsection
